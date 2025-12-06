<?php

namespace App\Repositories;

use App\Interfaces\SectionOfLawRepositoryInterface;
use App\Models\SectionOfLaw;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SectionOfLawRepository implements SectionOfLawRepositoryInterface
{
    /**
     * Get all sections of laws with filters and pagination
     */
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = $this->sectionOfLawsQuery($filters, $perPage);
        return ($perPage == 0) ? $query->get() : $query->paginate($perPage);
    }



    /**
     * Get all sections of laws with filters and pagination
     */
    public function sectionOfLawsQuery(array $filters = [], int $perPage = 10)
    {
        $query = SectionOfLaw::with(['translations', 'attachments'])->filter($filters)->latest();
        return $query;
    }



    /**
     * Get section of law by ID with relations
     */
    public function getSectionOfLawById(int $id)
    {
        return SectionOfLaw::with(['translations', 'attachments'])->findOrFail($id);
    }


    /**
     * Create a new section of law
     */
    public function create(array $data): SectionOfLaw
    {
        return DB::transaction(function () use ($data) {
            // Create section of law
            $sectionOfLaw = SectionOfLaw::create([
                'active' => $data['active'] ?? true,
            ]);

            // Set translations
            $sectionOfLaw->setTranslation('name', 'en', $data['name_en']);
            $sectionOfLaw->setTranslation('name', 'ar', $data['name_ar']);
            $sectionOfLaw->setTranslation('details', 'en', $data['details_en']);
            $sectionOfLaw->setTranslation('details', 'ar', $data['details_ar']);
            $sectionOfLaw->save();

            // Handle image upload
            if (isset($data['image'])) {
                $this->storeImage($sectionOfLaw, $data['image']);
            }

            return $sectionOfLaw->fresh(['translations', 'attachments']);
        });
    }

    /**
     * Update a section of law
     */
    public function update(SectionOfLaw $sectionOfLaw, array $data): SectionOfLaw
    {
        return DB::transaction(function () use ($sectionOfLaw, $data) {
            // Update basic fields
            $sectionOfLaw->update([
                'active' => $data['active'] ?? true,
            ]);

            // Update translations
            $sectionOfLaw->setTranslation('name', 'en', $data['name_en']);
            $sectionOfLaw->setTranslation('name', 'ar', $data['name_ar']);
            $sectionOfLaw->setTranslation('details', 'en', $data['details_en']);
            $sectionOfLaw->setTranslation('details', 'ar', $data['details_ar']);
            $sectionOfLaw->save();

            // Handle image upload
            if (isset($data['image'])) {
                // Delete old image if exists
                $oldImage = $sectionOfLaw->attachments()->where('type', 'image')->first();
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage->path);
                    $oldImage->delete();
                }

                // Store new image
                $this->storeImage($sectionOfLaw, $data['image']);
            }

            return $sectionOfLaw->fresh(['translations', 'attachments']);
        });
    }

    /**
     * Delete a section of law
     */
    public function delete(SectionOfLaw $sectionOfLaw): bool
    {
        return DB::transaction(function () use ($sectionOfLaw) {
            // Delete all attachments
            foreach ($sectionOfLaw->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
            }

            return $sectionOfLaw->delete();
        });
    }

    /**
     * Store image for section of law
     */
    private function storeImage(SectionOfLaw $sectionOfLaw, $imageFile): void
    {
        $path = $imageFile->store("sections_of_laws/{$sectionOfLaw->id}", 'public');
        $sectionOfLaw->attachments()->create([
            'path' => $path,
            'type' => 'image',
        ]);
    }
}
