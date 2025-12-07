<?php

namespace App\Http\Controllers\Api\v1\Lawyer;

use App\Http\Controllers\Controller;
use App\Services\AppointmentService;
use App\Traits\Res;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use Res;

    public function __construct(
        protected AppointmentService $appointmentService
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

            if ($perPage > 0) {
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
                'status' => 'required|in:approved,rejected,completed,cancelled',
                'cancellation_reason' => 'required_if:status,cancelled|string|nullable'
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
            if ($request->status === 'cancelled' && $request->has('cancellation_reason')) {
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
}
