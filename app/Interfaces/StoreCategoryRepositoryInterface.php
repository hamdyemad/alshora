<?php

namespace App\Interfaces;

use App\Models\StoreCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StoreCategoryRepositoryInterface
{
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function getAll();
    public function getCategoryById(int $id): StoreCategory;
    public function create(array $data): StoreCategory;
    public function update(StoreCategory $category, array $data): StoreCategory;
    public function delete(StoreCategory $category): bool;
}

