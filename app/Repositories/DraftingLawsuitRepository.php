<?php

namespace App\Repositories;

use App\Interfaces\DraftingLawsuitRepositoryInterface;
use App\Models\DraftingLawsuit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DraftingLawsuitRepository implements DraftingLawsuitRepositoryInterface
{
    /**
     * Get all drafting lawsuits with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = DraftingLawsuit::query();

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'like', "%{$search}%");
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
     * Get drafting lawsuit by ID
     */
    public function getDraftingLawsuitById(int $id)
    {
        return DraftingLawsuit::findOrFail($id);
    }

    /**
     * Get all drafting lawsuits
     */
    public function getAll()
    {
        return DraftingLawsuit::all();
    }

    /**
     * Create a new drafting lawsuit
     */
    public function create(array $data): DraftingLawsuit
    {
        return DB::transaction(function () use ($data) {
            $lawsuit = DraftingLawsuit::create([
                'name' => $data['name'] ?? null,
                'file' => $data['file'] ?? null,
                'active' => $data['active'] ?? true,
            ]);

            return $lawsuit->fresh();
        });
    }

    /**
     * Update a drafting lawsuit
     */
    public function update(DraftingLawsuit $draftingLawsuit, array $data): DraftingLawsuit
    {
        return DB::transaction(function () use ($draftingLawsuit, $data) {
            $updateData = [];

            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
            }
            if (isset($data['active'])) {
                $updateData['active'] = $data['active'];
            }

            // Handle file upload
            if (isset($data['file'])) {
                if ($draftingLawsuit->file) {
                    Storage::disk('public')->delete($draftingLawsuit->file);
                }
                $updateData['file'] = $data['file']->store('drafting_lawsuits', 'public');
            }

            $draftingLawsuit->update($updateData);

            return $draftingLawsuit->fresh();
        });
    }

    /**
     * Delete a drafting lawsuit
     */
    public function delete(DraftingLawsuit $draftingLawsuit): bool
    {
        return DB::transaction(function () use ($draftingLawsuit) {
            if ($draftingLawsuit->file) {
                Storage::disk('public')->delete($draftingLawsuit->file);
            }

            return $draftingLawsuit->delete();
        });
    }
}
