<?php

namespace App\Repositories;

use App\Interfaces\BranchOfLawRepositoryInterface;
use App\Models\BranchOfLaw;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BranchOfLawRepository implements BranchOfLawRepositoryInterface
{
    /**
     * Get all branches of laws with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = BranchOfLaw::with(['translations', 'attachments'])->withCount('laws');

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('lang_value', 'like', "%{$search}%")
                  ->where('lang_key', 'title');
            });
        }

        // Apply active filter
        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }

        // Apply date from filter
        if (!empty($filters['created_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_date_from']);
        }

        // Apply date to filter
        if (!empty($filters['created_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_date_to']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get branch of law by ID with relations
     */
    public function getBranchOfLawById(int $id)
    {
        return BranchOfLaw::with(['translations', 'attachments'])->findOrFail($id);
    }

    /**
     * Get all branches of laws
     */
    public function getAll()
    {
        return BranchOfLaw::with(['translations', 'attachments'])->get();
    }

    /**
     * Create a new branch of law
     */
    public function create(array $data): BranchOfLaw
    {
        return DB::transaction(function () use ($data) {
            // Create branch of law
            $branchOfLaw = BranchOfLaw::create([
                'active' => $data['active'] ?? true,
            ]);

            // Set translations
            $branchOfLaw->setTranslation('title', 'en', $data['title_en']);
            $branchOfLaw->setTranslation('title', 'ar', $data['title_ar']);
            $branchOfLaw->save();

            // Handle image upload
            if (isset($data['image'])) {
                $this->storeImage($branchOfLaw, $data['image']);
            }

            return $branchOfLaw->fresh(['translations', 'attachments']);
        });
    }

    /**
     * Update a branch of law
     */
    public function update(BranchOfLaw $branchOfLaw, array $data): BranchOfLaw
    {
        return DB::transaction(function () use ($branchOfLaw, $data) {
            // Update basic fields
            $branchOfLaw->update([
                'active' => $data['active'] ?? true,
            ]);

            // Update translations
            $branchOfLaw->setTranslation('title', 'en', $data['title_en']);
            $branchOfLaw->setTranslation('title', 'ar', $data['title_ar']);
            $branchOfLaw->save();

            // Handle image upload
            if (isset($data['image'])) {
                // Delete old image if exists
                $oldImage = $branchOfLaw->attachments()->where('type', 'image')->first();
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage->path);
                    $oldImage->delete();
                }
                
                // Store new image
                $this->storeImage($branchOfLaw, $data['image']);
            }

            return $branchOfLaw->fresh(['translations', 'attachments']);
        });
    }

    /**
     * Delete a branch of law
     */
    public function delete(BranchOfLaw $branchOfLaw): bool
    {
        return DB::transaction(function () use ($branchOfLaw) {
            // Delete all attachments
            foreach ($branchOfLaw->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
            }

            return $branchOfLaw->delete();
        });
    }

    /**
     * Store image for branch of law
     */
    private function storeImage(BranchOfLaw $branchOfLaw, $imageFile): void
    {
        $path = $imageFile->store("branches_of_laws/{$branchOfLaw->id}", 'public');
        $branchOfLaw->attachments()->create([
            'path' => $path,
            'type' => 'image',
        ]);
    }
}
