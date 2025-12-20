<?php

namespace App\Interfaces;

use App\Models\StoreProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StoreProductRepositoryInterface
{
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function getAll();
    public function getProductById(int $id): StoreProduct;
    public function create(array $data): StoreProduct;
    public function update(StoreProduct $product, array $data): StoreProduct;
    public function delete(StoreProduct $product): bool;
}

