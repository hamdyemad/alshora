<?php

namespace App\Interfaces;

use App\Models\DraftingContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DraftingContractRepositoryInterface
{
    /**
     * Get all drafting contracts with filters
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    /**
     * Get drafting contract by ID
     */
    public function getDraftingContractById(int $id);

    public function getAll();

    /**
     * Create a new drafting contract
     */
    public function create(array $data): DraftingContract;

    /**
     * Update a drafting contract
     */
    public function update(DraftingContract $draftingContract, array $data): DraftingContract;

    /**
     * Delete a drafting contract
     */
    public function delete(DraftingContract $draftingContract): bool;
}
