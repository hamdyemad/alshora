<?php

namespace App\Interfaces;

use App\Models\Lawyer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface LawyerRepositoryInterface
{
    /**
     * Get all lawyers with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    /**
     * Find a lawyer by ID with relations
     */
    public function findById($id): ?Lawyer;

    /**
     * Create a new lawyer
     */
    public function create(array $data);

    /**
     * Update a lawyer
     */
    public function update(Lawyer $lawyer, array $data): Lawyer;

    /**
     * Delete a lawyer
     */
    public function delete(Lawyer $lawyer): bool;

    /**
     * Get all lawyers without pagination
     */
    public function getAll(): Collection;

    /**
     * Set translation for a lawyer
     */
    public function setTranslation(Lawyer $lawyer, string $key, string $locale, string $value): void;

    /**
     * Store image attachment for lawyer
     */
    public function storeImage(Lawyer $lawyer, $file, string $type): void;

    /**
     * Delete image attachment by type
     */
    public function deleteImageByType(Lawyer $lawyer, string $type): void;

    /**
     * Delete all attachments for a lawyer
     */
    public function deleteAllAttachments(Lawyer $lawyer): void;


    public function updateOfficeHours(Lawyer $lawyer, array $officeHoursData);
}
