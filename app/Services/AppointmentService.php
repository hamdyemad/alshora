<?php

namespace App\Services;

use App\Interfaces\AppointmentRepositoryInterface;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Lawyer;
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseNotificationService;

class AppointmentService
{
    public function __construct(
        protected AppointmentRepositoryInterface $appointmentRepository
    ) {
    }

    /**
     * Get all appointments with filters
     */
    public function getAll(array $filters = [], int $perPage = 10)
    {
        try {
            return $this->appointmentRepository->getAll($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching appointments: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get appointment by ID
     */
    public function getAppointmentById(int $id)
    {
        try {
            return $this->appointmentRepository->findById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching appointment by ID: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reserve an appointment
     */
    public function reserve(array $data, Customer $customer)
    {
        try {
            // Check if lawyer exists and is active
            $lawyer = Lawyer::find($data['lawyer_id']);

            if (!$lawyer || !$lawyer->active) {
                throw new \Exception(__('appointment.lawyer_not_available'));
            }

            // Get office hour
            $officeHour = \App\Models\LawyerOfficeHour::find($data['lawyer_office_hour_id']);
            if (!$officeHour || $officeHour->lawyer_id != $data['lawyer_id']) {
                throw new \Exception(__('appointment.invalid_office_hour'));
            }

            // Calculate appointment date if not provided
            if (!isset($data['appointment_date'])) {
                $appointmentDate = \Carbon\Carbon::parse($officeHour->day);
                $timeSlot = \Carbon\Carbon::parse($appointmentDate->format('Y-m-d') . ' ' . $officeHour->from_time);

                // If the slot is in the past (e.g. today but earlier time), move to next week
                if ($timeSlot->isPast()) {
                    $appointmentDate->addWeek();
                }

                $data['appointment_date'] = $appointmentDate->format('Y-m-d');
            } else {
                // Validate provided date matches day
                $dayName = strtolower(date('l', strtotime($data['appointment_date'])));
                if ($dayName !== $officeHour->day) {
                    throw new \Exception(__('appointment.date_does_not_match_day'));
                }
            }

            // Set derived data
            $data['day'] = $officeHour->day;
            $data['period'] = $officeHour->period;
            $data['time_slot'] = $officeHour->from_time;

            // Check if the time slot is available
            if (!$this->appointmentRepository->isTimeSlotAvailable(
                $data['lawyer_id'],
                $data['appointment_date'],
                $data['time_slot']
            )) {
                throw new \Exception(__('appointment.time_slot_already_booked'));
            }

            // Add customer_id to data
            $data['customer_id'] = $customer->id;

            // Create appointment
            $appointment = $this->appointmentRepository->create($data);

            // Notify Lawyer via Database
            if ($lawyer->user) {
                $lawyer->user->notify(new \App\Notifications\NewBookingNotification($appointment));
            }

            // Send Firebase notification to lawyer
            try {
                $firebaseService = app(FirebaseNotificationService::class);
                $firebaseService->sendAppointmentNotificationToLawyer($lawyer, $appointment, $customer);
            } catch (\Exception $e) {
            }

            return $appointment;
        } catch (\Exception $e) {
            Log::error('Error reserving appointment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get customer appointments
     */
    public function getCustomerAppointments(Customer $customer, array $filters = [], int $perPage = 10)
    {
        try {
            return $this->appointmentRepository->getCustomerAppointments($customer, $filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching customer appointments: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cancel appointment
     */
    public function cancelAppointment(Appointment $appointment, Customer $customer, ?string $reason = null)
    {
        try {
            // Verify appointment belongs to customer
            if ($appointment->customer_id !== $customer->id) {
                throw new \Exception(__('appointment.not_found'));
            }

            // Check if already cancelled
            if ($appointment->status === 'cancelled') {
                throw new \Exception(__('appointment.already_cancelled'));
            }

            // Check if completed
            if ($appointment->status === 'completed') {
                throw new \Exception(__('appointment.cannot_cancel_completed'));
            }

            return $this->appointmentRepository->cancel($appointment, $reason);
        } catch (\Exception $e) {
            Log::error('Error cancelling appointment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update appointment
     */
    public function updateAppointment(Appointment $appointment, array $data)
    {
        try {
            return $this->appointmentRepository->update($appointment, $data);
        } catch (\Exception $e) {
            Log::error('Error updating appointment: ' . $e->getMessage());
            throw $e;
        }
    }
}
