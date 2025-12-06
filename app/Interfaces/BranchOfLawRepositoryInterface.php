<?php

namespace App\Interfaces;

use App\Models\BranchOfLaw;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BranchOfLawRepositoryInterface
{
    /**
     * Get all branches of laws with filters
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    /**
     * Get branch of law by ID
     */
    public function getBranchOfLawById(int $id);

    public function getAll();

    /**
     * Create a new branch of law
     */
    public function create(array $data): BranchOfLaw;

    /**
     * Update a branch of law
     */
    public function update(BranchOfLaw $branchOfLaw, array $data): BranchOfLaw;

    /**
     * Delete a branch of law
     */
    public function delete(BranchOfLaw $branchOfLaw): bool;
}
