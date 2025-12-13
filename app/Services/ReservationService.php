<?php

namespace App\Services;

use App\Interfaces\ReservationRepositoryInterface;
use App\Models\Appointment;
use App\Models\Lawyer;
use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ReservationService
{
    public function __construct(
        protected ReservationRepositoryInterface $reservationRepository
    ) {}

    /**
     * Get all appointments with filters and pagination
     */
    public function getAllAppointments(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->reservationRepository->getAllWithFilters($filters, $perPage);
    }

    /**
     * Get appointment by ID
     */
    public function getAppointmentById(int $id): ?Appointment
    {
        return $this->reservationRepository->findWithRelations($id);
    }

    /**
     * Update appointment status
     */
    public function updateAppointmentStatus(int $id, string $status): bool
    {
        // Validate status
        $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid status provided');
        }

        return $this->reservationRepository->updateStatus($id, $status);
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        $statusCounts = $this->reservationRepository->getCountByStatus();
        $recentAppointments = $this->reservationRepository->getRecentAppointments(5);

        return [
            'total_appointments' => array_sum($statusCounts),
            'pending_count' => $statusCounts['pending'] ?? 0,
            'confirmed_count' => $statusCounts['confirmed'] ?? 0,
            'completed_count' => $statusCounts['completed'] ?? 0,
            'cancelled_count' => $statusCounts['cancelled'] ?? 0,
            'recent_appointments' => $recentAppointments,
            'status_counts' => $statusCounts
        ];
    }

    /**
     * Get appointments for a specific lawyer
     */
    public function getLawyerAppointments(int $lawyerId, array $filters = []): Collection
    {
        return $this->reservationRepository->getByLawyer($lawyerId, $filters);
    }

    /**
     * Get appointments for a specific customer
     */
    public function getCustomerAppointments(int $customerId, array $filters = []): Collection
    {
        return $this->reservationRepository->getByCustomer($customerId, $filters);
    }

    /**
     * Get appointments in date range
     */
    public function getAppointmentsByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->reservationRepository->getByDateRange($startDate, $endDate);
    }

    /**
     * Get filter options for the reservation interface
     */
    public function getFilterOptions(): array
    {
        $lawyers = Lawyer::with('user')->get();
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        return [
            'lawyers' => $lawyers,
            'statuses' => $statuses
        ];
    }

    /**
     * Validate appointment filters
     */
    public function validateFilters(array $filters): array
    {
        $validatedFilters = [];

        // Validate and sanitize search
        if (!empty($filters['search'])) {
            $validatedFilters['search'] = trim($filters['search']);
        }

        // Validate status
        if (!empty($filters['status'])) {
            $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
            if (in_array($filters['status'], $validStatuses)) {
                $validatedFilters['status'] = $filters['status'];
            }
        }

        // Validate lawyer_id
        if (!empty($filters['lawyer_id']) && is_numeric($filters['lawyer_id'])) {
            $validatedFilters['lawyer_id'] = (int) $filters['lawyer_id'];
        }

        // Validate customer_id
        if (!empty($filters['customer_id']) && is_numeric($filters['customer_id'])) {
            $validatedFilters['customer_id'] = (int) $filters['customer_id'];
        }

        // Validate dates
        if (!empty($filters['date_from']) && strtotime($filters['date_from'])) {
            $validatedFilters['date_from'] = $filters['date_from'];
        }

        if (!empty($filters['date_to']) && strtotime($filters['date_to'])) {
            $validatedFilters['date_to'] = $filters['date_to'];
        }

        return $validatedFilters;
    }
}
