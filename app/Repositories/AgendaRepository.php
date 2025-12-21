<?php

namespace App\Repositories;

use App\Interfaces\AgendaRepositoryInterface;
use App\Models\Agenda;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AgendaRepository implements AgendaRepositoryInterface
{
    /**
     * Get all agendas with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Agenda::query()->with('user');

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('action_number', 'like', "%{$search}%")
                  ->orWhere('action_subject', 'like', "%{$search}%")
                  ->orWhere('claiment_name', 'like', "%{$search}%")
                  ->orWhere('defendant_name', 'like', "%{$search}%")
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
    public function findById(int $id): ?Agenda
    {
        return Agenda::with('user')->find($id);
    }

    /**
     * Create a new agenda item
     */
    public function create(array $data): Agenda
    {
        return Agenda::create($data);
    }

    /**
     * Update an agenda item
     */
    public function update(Agenda $agenda, array $data): Agenda
    {
        $agenda->update($data);
        return $agenda;
    }

    /**
     * Delete an agenda item
     */
    public function delete(Agenda $agenda): bool
    {
        return $agenda->delete();
    }

    /**
     * Get all agendas without pagination
     */
    public function getAll(): Collection
    {
        return Agenda::latest('datetime')->get();
    }
}
