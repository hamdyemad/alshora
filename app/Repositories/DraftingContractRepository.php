<?php

namespace App\Repositories;

use App\Interfaces\DraftingContractRepositoryInterface;
use App\Models\DraftingContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DraftingContractRepository implements DraftingContractRepositoryInterface
{
    /**
     * Get all drafting contracts with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = DraftingContract::query();

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%");
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
     * Get drafting contract by ID
     */
    public function getDraftingContractById(int $id)
    {
        return DraftingContract::findOrFail($id);
    }

    /**
     * Get all drafting contracts
     */
    public function getAll()
    {
        return DraftingContract::all();
    }

    /**
     * Create a new drafting contract
     */
    public function create(array $data): DraftingContract
    {
        return DB::transaction(function () use ($data) {
            $contract = DraftingContract::create([
                'name_en' => $data['name_en'] ?? null,
                'name_ar' => $data['name_ar'] ?? null,
                'file_en' => $data['file_en'] ?? null,
                'file_ar' => $data['file_ar'] ?? null,
                'active' => $data['active'] ?? true,
            ]);

            return $contract->fresh();
        });
    }

    /**
     * Update a drafting contract
     */
    public function update(DraftingContract $draftingContract, array $data): DraftingContract
    {
        return DB::transaction(function () use ($draftingContract, $data) {
            $updateData = [];

            if (isset($data['name_en'])) {
                $updateData['name_en'] = $data['name_en'];
            }
            if (isset($data['name_ar'])) {
                $updateData['name_ar'] = $data['name_ar'];
            }
            if (isset($data['active'])) {
                $updateData['active'] = $data['active'];
            }

            // Handle file uploads
            if (isset($data['file_en'])) {
                if ($draftingContract->file_en) {
                    Storage::disk('public')->delete($draftingContract->file_en);
                }
                $updateData['file_en'] = $data['file_en']->store('drafting_contracts', 'public');
            }

            if (isset($data['file_ar'])) {
                if ($draftingContract->file_ar) {
                    Storage::disk('public')->delete($draftingContract->file_ar);
                }
                $updateData['file_ar'] = $data['file_ar']->store('drafting_contracts', 'public');
            }

            $draftingContract->update($updateData);

            return $draftingContract->fresh();
        });
    }

    /**
     * Delete a drafting contract
     */
    public function delete(DraftingContract $draftingContract): bool
    {
        return DB::transaction(function () use ($draftingContract) {
            if ($draftingContract->file_en) {
                Storage::disk('public')->delete($draftingContract->file_en);
            }
            if ($draftingContract->file_ar) {
                Storage::disk('public')->delete($draftingContract->file_ar);
            }

            return $draftingContract->delete();
        });
    }
}
