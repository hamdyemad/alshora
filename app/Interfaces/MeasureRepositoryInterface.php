<?php

namespace App\Interfaces;

use App\Models\Measure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MeasureRepositoryInterface
{
    /**
     * Get all measures with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    /**
     * Get all measures
     */
    public function getAll();

    /**
     * Get measure by ID
     */
    public function getMeasureById(int $id): Measure;

    /**
     * Create a new measure
     */
    public function create(array $data): Measure;

    /**
     * Update a measure
     */
    public function update(Measure $measure, array $data): Measure;

    /**
     * Delete a measure
     */
    public function delete(Measure $measure): bool;
}
