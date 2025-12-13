<?php

namespace App\Repositories;

use App\Interfaces\ReservationRepositoryInterface;
use App\Models\Appointment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ReservationRepository implements ReservationRepositoryInterface
{
    /**
     * Get all appointments with filters and pagination
     */
    public function getAllWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Appointment::with(['lawyer.user', 'customer.user'])
            ->select('appointments.*')
            ->join('lawyers', 'appointments.lawyer_id', '=', 'lawyers.id')
            ->join('users as lawyer_users', 'lawyers.user_id', '=', 'lawyer_users.id')
            ->join('customers', 'appointments.customer_id', '=', 'customers.id')
            ->join('users as customer_users', 'customers.user_id', '=', 'customer_users.id');

        // Search functionality
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('lawyer_users.name', 'like', "%{$search}%")
                  ->orWhere('customer_users.name', 'like', "%{$search}%")
                  ->orWhere('appointments.appointment_date', 'like', "%{$search}%")
                  ->orWhere('appointments.time_slot', 'like', "%{$search}%");
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('appointments.status', $filters['status']);
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $query->where('appointments.appointment_date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('appointments.appointment_date', '<=', $filters['date_to']);
        }

        // Lawyer filter
        if (!empty($filters['lawyer_id'])) {
            $query->where('appointments.lawyer_id', $filters['lawyer_id']);
        }

        // Customer filter
        if (!empty($filters['customer_id'])) {
            $query->where('appointments.customer_id', $filters['customer_id']);
        }

        return $query->orderBy('appointments.created_at', 'desc')->paginate($perPage);
    }

    /**
     * Find appointment by ID with relationships
     */
    public function findWithRelations(int $id): ?Appointment
    {
        return Appointment::with([
            'lawyer.user',
            'customer.user',
            'lawyer.city',
            'lawyer.region'
        ])->find($id);
    }

    /**
     * Update appointment status
     */
    public function updateStatus(int $id, string $status): bool
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return false;
        }

        $appointment->status = $status;
        return $appointment->save();
    }

    /**
     * Get appointments count by status
     */
    public function getCountByStatus(): array
    {
        return Appointment::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get recent appointments
     */
    public function getRecentAppointments(int $limit = 10): Collection
    {
        return Appointment::with(['lawyer.user', 'customer.user'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get appointments for specific lawyer
     */
    public function getByLawyer(int $lawyerId, array $filters = []): Collection
    {
        $query = Appointment::with(['customer.user'])
            ->where('lawyer_id', $lawyerId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('appointment_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('appointment_date', '<=', $filters['date_to']);
        }

        return $query->orderBy('appointment_date', 'desc')->get();
    }

    /**
     * Get appointments for specific customer
     */
    public function getByCustomer(int $customerId, array $filters = []): Collection
    {
        $query = Appointment::with(['lawyer.user'])
            ->where('customer_id', $customerId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('appointment_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('appointment_date', '<=', $filters['date_to']);
        }

        return $query->orderBy('appointment_date', 'desc')->get();
    }

    /**
     * Get appointments by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return Appointment::with(['lawyer.user', 'customer.user'])
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->orderBy('appointment_date', 'asc')
            ->get();
    }
}
