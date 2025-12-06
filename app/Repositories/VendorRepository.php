<?php

namespace App\Repositories;

use App\Interfaces\VendorInterface;
use App\Models\Vendor;
use App\Models\Attachment;
use App\Models\User;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class VendorRepository implements VendorInterface
{
    public function getAllVendors(array $filters = [], int $perPage = 10)
    {
        $query = Vendor::with(['user', 'country', 'activity']);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        if (!empty($filters['activity_id'])) {
            $query->where('activity_id', $filters['activity_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getVendorById(int $id)
    {
        return Vendor::with(['user', 'country', 'activity', 'documents'])->findOrFail($id);
    }

    public function createVendor(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Create user account
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'user_type_id' => 2, // Vendor type
                'status' => true,
            ]);


            // Get default name from translations (first available)
            $defaultName = 'Vendor'; // Fallback
            $defaultDescription = null;
            
            if (!empty($data['translations'])) {
                foreach ($data['translations'] as $languageId => $fields) {
                    if (!empty($fields['name'])) {
                        $defaultName = $fields['name'];
                        $defaultDescription = $fields['description'] ?? null;
                        break;
                    }
                }
            }

            // Create vendor
            $vendor = Vendor::create([
                'name' => $defaultName,
                'description' => $defaultDescription,
                'country_id' => $data['country_id'],
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'meta_keywords' => $data['meta_keywords'] ?? null,
                'user_id' => $user->id,
                'status' => true,
            ]);


            // Handle logo upload
            if (isset($data['logo'])) {
                $logoPath = $data['logo']->store("vendors/$vendor->id/logo", 'public');
                $vendor->attachments()->create([
                    'path' => $logoPath,
                    'type' => 'logo',
                ]);
            }

            // Handle banner upload
            if (isset($data['banner'])) {
                $bannerPath = $data['banner']->store("vendors/$vendor->id/banner", 'public');
                $vendor->attachments()->create([
                    'path' => $bannerPath,
                    'type' => 'banner',
                ]);
            }


            // Sync activities (many-to-many relationship)
            if (!empty($data['activity_ids'])) {
                $vendor->activities()->sync($data['activity_ids']);
            }

            // Store translations
            $this->storeTranslations($vendor, $data);

            // Handle documents
            if (!empty($data['documents'])) {
                $this->storeDocuments($vendor, $data['documents']);
            }

            return $vendor->load(['user', 'country', 'activities', 'documents']);
        });
    }

    public function updateVendor(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $vendor = Vendor::findOrFail($id);

            // Handle logo upload
            if (isset($data['logo'])) {
                if ($vendor->logo) {
                    Storage::disk('public')->delete($vendor->logo);
                }
                $data['logo'] = $data['logo']->store('vendors/logos', 'public');
            }

            // Handle banner upload
            if (isset($data['banner'])) {
                if ($vendor->banner) {
                    Storage::disk('public')->delete($vendor->banner);
                }
                $data['banner'] = $data['banner']->store('vendors/banners', 'public');
            }

            // Get default name from translations (first available)
            $defaultName = $vendor->name; // Keep existing if no translations
            $defaultDescription = $vendor->description;
            
            if (!empty($data['translations'])) {
                foreach ($data['translations'] as $languageId => $fields) {
                    if (!empty($fields['name'])) {
                        $defaultName = $fields['name'];
                        $defaultDescription = $fields['description'] ?? null;
                        break;
                    }
                }
            }

            // Update vendor
            $vendor->update([
                'name' => $defaultName,
                'description' => $defaultDescription,
                'logo' => $data['logo'] ?? $vendor->logo,
                'banner' => $data['banner'] ?? $vendor->banner,
                'country_id' => $data['country_id'],
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'meta_keywords' => $data['meta_keywords'] ?? null,
            ]);

            // Sync activities (many-to-many relationship)
            if (!empty($data['activity_ids'])) {
                $vendor->activities()->sync($data['activity_ids']);
            }

            // Update translations
            $this->storeTranslations($vendor, $data);

            // Handle documents
            if (!empty($data['documents'])) {
                $this->storeDocuments($vendor, $data['documents']);
            }

            return $vendor->load(['user', 'country', 'activities', 'documents']);
        });
    }

    public function deleteVendor(int $id)
    {
        $vendor = Vendor::findOrFail($id);
        foreach ($vendor->attachments as $attachment) {
            if ($attachment->path) {
                if(file_exists($attachment->path)) {
                    Storage::disk('public')->delete($attachment->path);
                }
            }
            $attachment->delete();
        }

        return $vendor->delete();
    }

    /**
     * Store translations for vendor
     */
    protected function storeTranslations(Vendor $vendor, array $data)
    {
        // Delete existing translations
        $vendor->translations()->delete();

        // Handle translations array from form (translations[language_id][name/description])
        if (!empty($data['translations'])) {
            foreach ($data['translations'] as $languageId => $fields) {
                // Get language code from language ID
                $language = \App\Models\Language::find($languageId);
                if (!$language) {
                    continue;
                }

                // Store name translation
                if (!empty($fields['name'])) {
                    $vendor->translations()->create([
                        'locale' => $language->code,
                        'key' => 'name',
                        'value' => $fields['name'],
                    ]);
                }

                // Store description translation
                if (!empty($fields['description'])) {
                    $vendor->translations()->create([
                        'locale' => $language->code,
                        'key' => 'description',
                        'value' => $fields['description'],
                    ]);
                }
            }
        }
    }

    /**
     * Store documents for vendor using Attachment model
     */
    protected function storeDocuments(Vendor $vendor, array $documents)
    {
        foreach ($documents as $documentData) {
            if (empty($documentData['file'])) {
                continue;
            }

            $file = $documentData['file'];
            $filePath = $file->store("vendors/{$vendor->id}/documents", 'public');

            $attachment = $vendor->attachments()->create([
                'type' => 'docs',
                'path' => $filePath,
                'size' => $file->getSize(),
            ]);
        }
    }
}
