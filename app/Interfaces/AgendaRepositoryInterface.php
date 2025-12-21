<?php

namespace App\Interfaces;

use App\Models\Agenda;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AgendaRepositoryInterface
{
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function findById(int $id): ?Agenda;
    public function create(array $data): Agenda;
    public function update(Agenda $agenda, array $data): Agenda;
    public function delete(Agenda $agenda): bool;
    public function getAll(): Collection;
}
