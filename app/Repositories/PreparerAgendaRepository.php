<?php

namespace App\Repositories;

use App\Interfaces\PreparerAgendaRepositoryInterface;
use App\Models\PreparerAgenda;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PreparerAgendaRepository implements PreparerAgendaRepositoryInterface
{
    /**
     * Get all agendas with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = PreparerAgenda::query()->with('user');

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('court_bailiffs', 'like', "%{$search}%")
                  ->orWhere('paper_type', 'like', "%{$search}%")
                  ->orWhere('paper_number', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Apply date filter
        if (!empty($filters['date_from'])) {
            $query->whereDate('datetime', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('datetime', '<=', $filters['date_to']);
        }

        return $query->latest('datetime')->paginate($perPage);
    }

    /**
     * Find an agenda item by ID
     */
    public function findById(int $id): ?PreparerAgenda
    {
        return PreparerAgenda::with('user')->find($id);
    }

    /**
     * Create a new agenda item
     */
    public function create(array $data): PreparerAgenda
    {
        return PreparerAgenda::create($data);
    }

    /**
     * Update an agenda item
     */
    public function update(PreparerAgenda $agenda, array $data): PreparerAgenda
    {
        $agenda->update($data);
        return $agenda;
    }

    /**
     * Delete an agenda item
     */
    public function delete(PreparerAgenda $agenda): bool
    {
        return $agenda->delete();
    }

    /**
     * Get all agendas without pagination
     */
    public function getAll(): Collection
    {
        return PreparerAgenda::latest('datetime')->get();
    }
}
