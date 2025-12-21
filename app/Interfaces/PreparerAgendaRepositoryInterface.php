<?php

namespace App\Interfaces;

use App\Models\PreparerAgenda;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PreparerAgendaRepositoryInterface
{
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function findById(int $id): ?PreparerAgenda;
    public function create(array $data): PreparerAgenda;
    public function update(PreparerAgenda $agenda, array $data): PreparerAgenda;
    public function delete(PreparerAgenda $agenda): bool;
    public function getAll(): Collection;
}
