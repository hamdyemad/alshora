<?php

namespace App\Repositories;

use App\Interfaces\LawyerRepositoryInterface;
use App\Models\Lawyer;
use App\Models\LawyerOfficeHour;
use App\Models\Attachment;
use App\Models\UserType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LawyerRepository implements LawyerRepositoryInterface
{
    /**
     * Get all lawyers with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Lawyer::with(['user', 'city', 'region', 'phoneCountry', 'attachments', 'sectionsOfLaws']);

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('lang_value', 'like', "%{$search}%")
                  ->whereIn('lang_key', ['name']);
            });
        }

        // Apply active filter
        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }

        // Apply active filter
        if (isset($filters['section_of_law_id']) && $filters['section_of_law_id'] !== '') {
            $query->whereHas('sectionsOfLaws', function ($q) use ($filters) {
                $q->where('section_of_law_id', $filters['section_of_law_id']);
            });
        }

        // Apply date from filter
        if (!empty($filters['created_date_from'])) {
            $query->whereDate('date', '>=', $filters['created_date_from']);
        }

        // Apply date to filter
        if (!empty($filters['created_date_to'])) {
            $query->whereDate('date', '<=', $filters['created_date_to']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find a lawyer by ID with relations
     */
    public function findById($id): ?Lawyer
    {
        return Lawyer::with(['user', 'city', 'sectionsOfLaws', 'region', 'phoneCountry', 'attachments', 'officeHours', 'subscription', 'sectionsOfLaws'])->find($id);
    }

    /**
     * Create a new lawyer
     */
    public function create(array $incommingData)
    {
        return DB::transaction(function () use ($incommingData) {
            $lawyerData = $this->lawyerData($incommingData);
            $lawyer = Lawyer::create($this->lawyerData($lawyerData));
            $user = $lawyer->user()->create([
                'uuid' => \Str::uuid(),
                'user_type_id' => UserType::LAWYER_TYPE,
                'email' => $incommingData['email'],
                'password' => Hash::make($incommingData['password']),
            ]);

            $lawyer->user_id = $user->id;
            $lawyer->save();
            if(isset($incommingData['profile_image'])) {
                // Store new image
                $path = $incommingData['profile_image']->store("lawyers/{$lawyer->id}", 'public');
                $lawyer->attachments()->create([
                    'path' => $path,
                    'type' => 'profile_image',
                ]);
            }

            if(isset($incommingData['id_card'])) {
                // Store new image
                $path = $incommingData['id_card']->store("lawyers/{$lawyer->id}", 'public');
                $lawyer->attachments()->create([
                    'path' => $path,
                    'type' => 'id_card',
                ]);
            }

            // Set Translations
            $lawyer->setTranslation('name', 'en', $incommingData['name_en']);
            $lawyer->setTranslation('name', 'ar', $incommingData['name_ar']);

            if(isset($incommingData['experience_en'])) {
                $lawyer->setTranslation('experience', 'en', $incommingData['experience_en']);
                $lawyer->setTranslation('experience', 'ar', $incommingData['experience_ar']);
            }

            $lawyer->save();

            // Save office hours
            if (isset($incommingData['office_hours'])) {
                $this->saveOfficeHours($lawyer, $incommingData['office_hours']);
            }

            // Sync sections of laws (specializations)
            if (isset($incommingData['sections_of_laws'])) {
                $lawyer->sectionsOfLaws()->sync($incommingData['sections_of_laws']);
            }

            return $lawyer;
        });


    }

    public function lawyerData($incommingData) {
        $data = [];
        $data['active'] = $incommingData['active'] ?? 0;
        (isset($incommingData['city_id'])) ? $data['city_id'] = $incommingData['city_id'] : null;
        (isset($incommingData['address'])) ? $data['address'] = $incommingData['address'] : null;
        (isset($incommingData['region_id'])) ? $data['region_id'] = $incommingData['region_id'] : null;
        (isset($incommingData['consultation_price'])) ? $data['consultation_price'] = $incommingData['consultation_price'] : null;
        (isset($incommingData['gender'])) ? $data['gender'] = $incommingData['gender'] : null;
        (isset($incommingData['phone_country_id'])) ? $data['phone_country_id'] = $incommingData['phone_country_id'] : null;
        (isset($incommingData['phone'])) ? $data['phone'] = $incommingData['phone'] : null;
        (isset($incommingData['latitude'])) ? $data['latitude'] = $incommingData['latitude'] : null;
        (isset($incommingData['longitude'])) ? $data['longitude'] = $incommingData['longitude'] : null;
        (isset($incommingData['degree_of_registration_id'])) ? $data['degree_of_registration_id'] = $incommingData['degree_of_registration_id'] : null;
        (isset($incommingData['facebook_url'])) ? $data['facebook_url'] = $incommingData['facebook_url'] : null;
        (isset($incommingData['instagram_url'])) ? $data['instagram_url'] = $incommingData['instagram_url'] : null;
        (isset($incommingData['telegram_url'])) ? $data['telegram_url'] = $incommingData['telegram_url'] : null;
        (isset($incommingData['twitter_url'])) ? $data['twitter_url'] = $incommingData['twitter_url'] : null;
        (isset($incommingData['registration_number'])) ? $data['registration_number'] = $incommingData['registration_number'] : null;
        return $data;
    }

    /**
     * Update a lawyer
     */
    public function update(Lawyer $lawyer, array $incommingData): Lawyer
    {
        return DB::transaction(function () use ($lawyer, $incommingData) {
            // Update user email and password
            $userData = [];
            if (isset($incommingData['email'])) {
                $userData['email'] = $incommingData['email'];
            }
            if (isset($incommingData['password']) && !empty($incommingData['password'])) {
                $userData['password'] = Hash::make($incommingData['password']);
            }
            if (!empty($userData)) {
                $lawyer->user->update($userData);
            }

            // Update lawyer data
            $lawyerData = $this->lawyerData($incommingData);

            $lawyer->update($lawyerData);

            // Handle profile image upload
            if (isset($incommingData['profile_image'])) {
                $this->storeImage($lawyer, $incommingData['profile_image'], 'profile_image');
            }

            // Handle ID card image upload
            if (isset($incommingData['id_card'])) {
                $this->storeImage($lawyer, $incommingData['id_card'], 'id_card');
            }

            // Update translations
            if(isset($incommingData['name_en'])) {
                $lawyer->setTranslation('name', 'en', $incommingData['name_en']);
            }
            if(isset($incommingData['name_ar'])) {
                $lawyer->setTranslation('name', 'ar', $incommingData['name_ar']);
            }
            if(isset($incommingData['experience_en'])) {
                $lawyer->setTranslation('experience', 'en', $incommingData['experience_en']);
            }
            if(isset($incommingData['experience_ar'])) {
                $lawyer->setTranslation('experience', 'ar', $incommingData['experience_ar']);
            }
            $lawyer->save();

            // Save office hours
            if (isset($incommingData['office_hours'])) {
                $this->saveOfficeHours($lawyer, $incommingData['office_hours']);
            }

            // Sync sections of laws (specializations)
            if (isset($incommingData['sections_of_laws'])) {
                $lawyer->sectionsOfLaws()->sync($incommingData['sections_of_laws']);
            }

            return $lawyer->fresh(['user', 'city', 'region', 'phoneCountry', 'attachments', 'officeHours', 'sectionsOfLaws']);
        });
    }

    /**
     * Delete a lawyer
     */
    public function delete(Lawyer $lawyer): bool
    {
        return $lawyer->delete();
    }

    /**
     * Get all lawyers without pagination
     */
    public function getAll(): Collection
    {
        return Lawyer::with(['user', 'city', 'region', 'phoneCountry', 'sectionsOfLaws'])->latest()->get();
    }

    /**
     * Store image attachment for lawyer
     */
    public function storeImage(Lawyer $lawyer, $file, string $type): void
    {
        // Delete old image if exists
        $this->deleteImageByType($lawyer, $type);

        // Store new image
        $path = $file->store("lawyers/{$lawyer->id}/{$type}", 'public');

        $lawyer->attachments()->create([
            'path' => $path,
            'type' => $type,
            'size' => $file->getSize(),
        ]);
    }

    /**
     * Delete image attachment by type
     */
    public function deleteImageByType(Lawyer $lawyer, string $type): void
    {
        $attachment = $lawyer->attachments()->where('type', $type)->first();

        if ($attachment) {
            // Delete file from storage
            if (Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }

            // Delete attachment record
            $attachment->delete();
        }
    }

    /**
     * Delete all attachments for a lawyer
     */
    public function deleteAllAttachments(Lawyer $lawyer): void
    {
        foreach ($lawyer->attachments as $attachment) {
            if ($attachment->path && Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }
            $attachment->delete();
        }
    }

    /**
     * Save or update office hours for a lawyer
     */
    private function saveOfficeHours(Lawyer $lawyer, array $officeHoursData): void
    {
        foreach ($officeHoursData as $day => $periods) {
            foreach ($periods as $period => $data) {
                LawyerOfficeHour::updateOrCreate(
                    [
                        'lawyer_id' => $lawyer->id,
                        'day' => $day,
                        'period' => $period,
                    ],
                    [
                        'from_time' => $data['from_time'] ?? null,
                        'to_time' => $data['to_time'] ?? null,
                        'is_available' => isset($data['is_available']) && $data['is_available'] == 1,
                    ]
                );
            }
        }
    }

    /**
     * Update office hours for a lawyer (public method for direct calls)
     */
    public function updateOfficeHours(Lawyer $lawyer, array $officeHoursData): void
    {
        $this->saveOfficeHours($lawyer, $officeHoursData);
    }

    /**
     * Set translation for a lawyer
     */
    public function setTranslation(Lawyer $lawyer, string $key, string $locale, string $value): void
    {
        $lawyer->setTranslation($key, $locale, $value);
    }
}
