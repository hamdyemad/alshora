<?php

namespace App\Interfaces;

use App\Models\DraftingLawsuit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DraftingLawsuitRepositoryInterface
{
    /**
     * Get all drafting lawsuits with filters
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    /**
     * Get drafting lawsuit by ID
     */
    public function getDraftingLawsuitById(int $id);

    public function getAll();

    /**
     * Create a new drafting lawsuit
     */
    public function create(array $data): DraftingLawsuit;

    /**
     * Update a drafting lawsuit
     */
    public function update(DraftingLawsuit $draftingLawsuit, array $data): DraftingLawsuit;

    /**
     * Delete a drafting lawsuit
     */
    public function delete(DraftingLawsuit $draftingLawsuit): bool;
}
