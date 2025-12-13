<?php

namespace App\Repositories;

use App\Interfaces\AppointmentRepositoryInterface;
use App\Models\Appointment;
use App\Models\Customer;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    /**
     * Get all appointments with filters and pagination
     */
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = Appointment::with(['customer.user', 'lawyer.user', 'lawyer.phoneCountry'])
            ->latest();

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['lawyer_id'])) {
            $query->where('lawyer_id', $filters['lawyer_id']);
        }

        if (isset($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('appointment_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('appointment_date', '<=', $filters['date_to']);
        }

        return ($perPage == 0) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Find an appointment by ID
     */
    public function findById(int $id): ?Appointment
    {
        return Appointment::with(['customer.user', 'lawyer.user', 'lawyer.phoneCountry'])
            ->find($id);
    }

    /**
     * Create a new appointment
     */
    public function create(array $data): Appointment
    {
        return Appointment::create([
            'customer_id' => $data['customer_id'],
            'lawyer_id' => $data['lawyer_id'],
            'appointment_date' => $data['appointment_date'],
            'day' => $data['day'],
            'period' => $data['period'],
            'time_slot' => $data['time_slot'],
            'consultation_type' => $data['consultation_type'] ?? 'office',
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'] ?? 'pending',
        ]);
    }

    /**
     * Update an appointment
     */
    public function update(Appointment $appointment, array $data): Appointment
    {
        $appointment->update($data);
        return $appointment->fresh(['customer.user', 'lawyer.user']);
    }

    /**
     * Cancel an appointment
     */
    public function cancel(Appointment $appointment, ?string $reason = null): bool
    {
        return $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);
    }

    /**
     * Get customer appointments
     */
    public function getCustomerAppointments(Customer $customer, array $filters = [], int $perPage = 10)
    {
        $query = Appointment::with(['lawyer.user', 'lawyer.phoneCountry'])
            ->where('customer_id', $customer->id)
            ->latest();

        // Apply status filter
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return ($perPage == 0) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Check if time slot is available
     */
    public function isTimeSlotAvailable(int $lawyerId, string $date, ?string $timeSlot): bool
    {
        // If timeSlot is null, return false (not available)
        if ($timeSlot === null) {
            return false;
        }

        return !Appointment::where('lawyer_id', $lawyerId)
            ->where('appointment_date', $date)
            ->where('time_slot', $timeSlot)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
    }
}
