<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Models\LawyerTransaction;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\Log;

class AppointmentObserver
{
    protected $firebaseService;

    public function __construct(FirebaseNotificationService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Handle the Appointment "updated" event.
     * When appointment status changes, send notifications and record transactions
     */
    public function updated(Appointment $appointment): void
    {
        // Check if status changed
        if ($appointment->isDirty('status')) {
            $oldStatus = $appointment->getOriginal('status');
            $newStatus = $appointment->status;

            Log::info('Appointment status changed', [
                'appointment_id' => $appointment->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

            // Load relationships if not loaded
            $appointment->loadMissing(['customer.user', 'lawyer.user']);

            // Handle status-specific actions
            switch ($newStatus) {
                case 'approved':
                    $this->handleApproved($appointment);
                    break;

                case 'rejected':
                    $this->handleRejected($appointment);
                    break;

                case 'completed':
                    $this->handleCompleted($appointment);
                    break;

                case 'cancelled':
                    $this->handleCancelled($appointment);
                    break;
            }
        }
    }

    /**
     * Handle appointment approved
     */
    protected function handleApproved(Appointment $appointment): void
    {
        try {
            // Send notification to customer
            if ($appointment->customer && $appointment->customer->user && $appointment->lawyer) {
                $this->firebaseService->sendAppointmentApprovedNotification(
                    $appointment->customer->user,
                    $appointment,
                    $appointment->lawyer
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to send approved notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle appointment rejected
     */
    protected function handleRejected(Appointment $appointment): void
    {
        try {
            // Send notification to customer
            if ($appointment->customer && $appointment->customer->user && $appointment->lawyer) {
                $this->firebaseService->sendAppointmentRejectedNotification(
                    $appointment->customer->user,
                    $appointment,
                    $appointment->lawyer,
                    $appointment->cancellation_reason ?? __('appointment.no_reason_provided')
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to send rejected notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle appointment completed
     */
    protected function handleCompleted(Appointment $appointment): void
    {
        try {
            // Refresh appointment to ensure we have the latest data
            $appointment->refresh();
            
            // Create transaction if not exists
            $existingTransaction = LawyerTransaction::where('appointment_id', $appointment->id)->first();
            
            if (!$existingTransaction && $appointment->lawyer) {
                // Use consultation_price from appointment (not from lawyer)
                $amount = $appointment->consultation_price ?? 0;
                
                if ($amount > 0) {
                    $transaction = LawyerTransaction::create([
                        'lawyer_id' => $appointment->lawyer_id,
                        'appointment_id' => $appointment->id,
                        'type' => 'income',
                        'amount' => $amount,
                        'category' => 'consultation',
                        'description' => __('appointment.transaction_description', [
                            'customer' => $appointment->customer->getTranslation('name', app()->getLocale()),
                            'date' => $appointment->appointment_date->format('Y-m-d'),
                        ]),
                        'transaction_date' => now(),
                    ]);

                    Log::info('Transaction created for completed appointment', [
                        'appointment_id' => $appointment->id,
                        'transaction_id' => $transaction->id,
                        'amount' => $amount
                    ]);
                }
            }

            // Send notification to customer
            if ($appointment->customer && $appointment->customer->user && $appointment->lawyer) {
                $this->firebaseService->sendAppointmentStatusUpdate(
                    $appointment->customer->user,
                    $appointment,
                    'completed'
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to handle completed appointment: ' . $e->getMessage(), [
                'appointment_id' => $appointment->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle appointment cancelled
     */
    protected function handleCancelled(Appointment $appointment): void
    {
        try {
            // Send notification to lawyer
            if ($appointment->lawyer && $appointment->lawyer->user) {
                $this->firebaseService->sendAppointmentStatusUpdate(
                    $appointment->lawyer->user,
                    $appointment,
                    'cancelled'
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to send cancelled notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Appointment "deleted" event.
     * Remove associated transaction if appointment is deleted
     */
    public function deleted(Appointment $appointment): void
    {
        // Delete associated transaction
        LawyerTransaction::where('appointment_id', $appointment->id)->delete();
    }
}
