<?php

namespace App\Http\Controllers\Api\v1\Lawyer;

use App\Http\Controllers\Controller;
use App\Services\AppointmentService;
use App\Services\FirebaseNotificationService;
use App\Traits\Res;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use Res;

    public function __construct(
        protected AppointmentService $appointmentService,
        protected FirebaseNotificationService $firebaseService
    ) {
    }

    /**
     * Get lawyer's appointments
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $lawyer = $user->lawyer;

            if (!$lawyer) {
                return $this->sendRes(
                    __('appointment.lawyer_not_found'),
                    false,
                    [],
                    [],
                    404
                );
            }

            $filters = [];
            if ($request->has('status')) {
                $filters['status'] = $request->input('status');
            }
            $filters['lawyer_id'] = $lawyer->id;

            $perPage = $request->input('per_page', 10);
            $appointments = $this->appointmentService->getAll($filters, $perPage);

            if (isset($perPage) && $perPage > 0) {
                $data = [
                    'data' => $appointments->items(),
                    'pagination' => [
                        'current_page' => $appointments->currentPage(),
                        'last_page' => $appointments->lastPage(),
                        'per_page' => $appointments->perPage(),
                        'total' => $appointments->total(),
                        'from' => $appointments->firstItem(),
                        'to' => $appointments->lastItem(),
                    ]
                ];
                return $this->sendRes(__('validation.success'), true, $data);
            }

            return $this->sendRes(__('validation.success'), true, $appointments);
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,approved,rejected',
                'cancellation_reason' => 'required_if:status,rejected|string|nullable'
            ]);

            $user = $request->user();
            $lawyer = $user->lawyer;

            if (!$lawyer) {
                return $this->sendRes(__('appointment.lawyer_not_found'), false, [], [], 404);
            }

            $appointment = $this->appointmentService->getAppointmentById($id);

            if (!$appointment || $appointment->lawyer_id !== $lawyer->id) {
                return $this->sendRes(__('appointment.not_found'), false, [], [], 404);
            }

            $data = ['status' => $request->status];
            if ($request->status === 'rejected' && $request->has('cancellation_reason')) {
                $data['cancellation_reason'] = $request->cancellation_reason;
            }

            $this->appointmentService->updateAppointment($appointment, $data);

            return $this->sendRes(
                __('appointment.updated_successfully'),
                true,
                $appointment->fresh()
            );

        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Approve appointment
     */
    public function approve(Request $request, $id)
    {
        try {
            $request->validate([
                'notes' => 'nullable|string|max:500'
            ]);

            $user = $request->user();
            $lawyer = $user->lawyer;

            if (!$lawyer) {
                return $this->sendRes(__('appointment.lawyer_not_found'), false, [], [], 404);
            }

            $appointment = $this->appointmentService->getAppointmentById($id);

            if (!$appointment) {
                return $this->sendRes(__('appointment.not_found'), false, [], [], 404);
            }

            if ($appointment->lawyer_id !== $lawyer->id) {
                return $this->sendRes(__('appointment.unauthorized'), false, [], [], 403);
            }

            if ($appointment->status !== 'pending') {
                return $this->sendRes(__('appointment.cannot_approve_non_pending'), false, [], [], 400);
            }

            // Update appointment status
            $data = ['status' => 'approved'];
            if ($request->has('notes')) {
                $data['notes'] = $request->notes;
            }
            $this->appointmentService->updateAppointment($appointment, $data);

            // Send Firebase notification to customer
            if ($appointment->customer && $appointment->customer->user) {
                $this->firebaseService->sendAppointmentApprovedNotification(
                    $appointment->customer->user,
                    $appointment,
                    $lawyer
                );
            }

            return $this->sendRes(
                __('appointment.approved_successfully'),
                true,
                $appointment->fresh()
            );

        } catch (\Exception $e) {
            \Log::error('Appointment approval error: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Reject appointment
     */
    public function reject(Request $request, $id)
    {
        try {
            $request->validate([
                'cancellation_reason' => 'required|string|max:500'
            ]);

            $user = $request->user();
            $lawyer = $user->lawyer;

            if (!$lawyer) {
                return $this->sendRes(__('appointment.lawyer_not_found'), false, [], [], 404);
            }

            $appointment = $this->appointmentService->getAppointmentById($id);

            if (!$appointment) {
                return $this->sendRes(__('appointment.not_found'), false, [], [], 404);
            }

            if ($appointment->lawyer_id !== $lawyer->id) {
                return $this->sendRes(__('appointment.unauthorized'), false, [], [], 403);
            }

            if ($appointment->status !== 'pending') {
                return $this->sendRes(__('appointment.cannot_reject_non_pending'), false, [], [], 400);
            }

            // Update appointment status
            $data = [
                'status' => 'rejected',
                'cancellation_reason' => $request->cancellation_reason
            ];
            $this->appointmentService->updateAppointment($appointment, $data);

            // Send Firebase notification to customer
            if ($appointment->customer && $appointment->customer->user) {
                $this->firebaseService->sendAppointmentRejectedNotification(
                    $appointment->customer->user,
                    $appointment,
                    $lawyer,
                    $request->cancellation_reason
                );
            }

            return $this->sendRes(
                __('appointment.rejected_successfully'),
                true,
                $appointment->fresh()
            );

        } catch (\Exception $e) {
            \Log::error('Appointment rejection error: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}
