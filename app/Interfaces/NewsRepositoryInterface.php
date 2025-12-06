<?php

namespace App\Interfaces;

use App\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface NewsRepositoryInterface
{
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function findById(int $id): ?News;
    public function create(array $data): News;
    public function update(News $news, array $data): News;
    public function delete(News $news): bool;
    public function getAll(): Collection;
}
