<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Appointment;

interface ReservationRepositoryInterface
{
    /**
     * Get all appointments with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find appointment by ID with relationships
     */
    public function findWithRelations(int $id): ?Appointment;

    /**
     * Update appointment status
     */
    public function updateStatus(int $id, string $status): bool;

    /**
     * Get appointments count by status
     */
    public function getCountByStatus(): array;

    /**
     * Get recent appointments
     */
    public function getRecentAppointments(int $limit = 10): Collection;

    /**
     * Get appointments for specific lawyer
     */
    public function getByLawyer(int $lawyerId, array $filters = []): Collection;

    /**
     * Get appointments for specific customer
     */
    public function getByCustomer(int $customerId, array $filters = []): Collection;

    /**
     * Get appointments by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;
}
