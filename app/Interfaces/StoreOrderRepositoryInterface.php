<?php

namespace App\Interfaces;

use App\Models\StoreOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StoreOrderRepositoryInterface
{
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function getOrderById(int $id): StoreOrder;
    public function create(array $data): StoreOrder;
    public function update(StoreOrder $order, array $data): StoreOrder;
    public function delete(StoreOrder $order): bool;
}

