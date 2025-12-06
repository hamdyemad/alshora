<?php

namespace App\Interfaces;

use App\Models\SectionOfLaw;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SectionOfLawRepositoryInterface
{
    /**
     * Get all sections of laws with filters
     */
    public function getAll(array $filters = [], int $perPage = 10);

    /**
     * Get section of law by ID
     */
    public function getSectionOfLawById(int $id);


    /**
     * Create a new section of law
     */
    public function create(array $data): SectionOfLaw;

    /**
     * Update a section of law
     */
    public function update(SectionOfLaw $sectionOfLaw, array $data): SectionOfLaw;

    /**
     * Delete a section of law
     */
    public function delete(SectionOfLaw $sectionOfLaw): bool;
}
