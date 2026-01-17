<?php

namespace App\Interfaces;

use App\Models\Appointment;
use App\Models\Customer;

interface AppointmentRepositoryInterface
{
    /**
     * Get all appointments with filters and pagination
     */
    public function getAll(array $filters = [], int $perPage = 10);

    /**
     * Find an appointment by ID
     */
    public function findById(int $id): ?Appointment;

    /**
     * Create a new appointment
     */
    public function create(array $data): Appointment;

    /**
     * Update an appointment
     */
    public function update(Appointment $appointment, array $data): Appointment;

    /**
     * Cancel an appointment
     */
    public function cancel(Appointment $appointment, ?string $reason = null): bool;

    /**
     * Get customer appointments
     */
    public function getCustomerAppointments(Customer $customer, array $filters = [], int $perPage = 10);

    /**
     * Check if time slot is available
     */
    public function isTimeSlotAvailable(int $lawyerId, string $date, string $timeSlot): bool;

    /**
     * Complete an appointment
     */
    public function complete(Appointment $appointment): Appointment;
}
