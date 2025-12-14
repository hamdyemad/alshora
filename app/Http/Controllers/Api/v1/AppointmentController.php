<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReserveAppointmentRequest;
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
     * Reserve an appointment with a lawyer
     */
    public function reserve(ReserveAppointmentRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $request->user();

            // Get customer from authenticated user
            $customer = $user->customer;
            if (!$customer) {
                return $this->sendRes(
                    __('appointment.customer_not_found'),
                    false,
                    [],
                    [],
                    404
                );
            }

            // Reserve appointment through service
            $appointment = $this->appointmentService->reserve($data, $customer);

            return $this->sendRes(
                __('appointment.reserved_successfully'),
                true,
                [
                    'appointment_id' => $appointment->id,
                    'status' => $appointment->status,
                ],
                [],
                201
            );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    /**
     * Get customer's appointments
     */
    public function myAppointments(Request $request)
    {
        try {
            $user = $request->user();
            $customer = $user->customer;

            if (!$customer) {
                return $this->sendRes(
                    __('appointment.customer_not_found'),
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

            $perPage = $request->input('per_page', 10);
            $appointments = $this->appointmentService->getCustomerAppointments($customer, $filters, $perPage);

            if ($perPage > 0) {
                $data = [
                    'items' => $appointments->items(),
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
     * Cancel an appointment
     */
    public function cancel(Request $request, $appointmentId)
    {
        try {
            $user = $request->user();
            $customer = $user->customer;

            if (!$customer) {
                return $this->sendRes(
                    __('appointment.customer_not_found'),
                    false,
                    [],
                    [],
                    404
                );
            }

            $appointment = $this->appointmentService->getAppointmentById($appointmentId);

            if (!$appointment) {
                return $this->sendRes(
                    __('appointment.not_found'),
                    false,
                    [],
                    [],
                    404
                );
            }

            // Cancel through service
            $this->appointmentService->cancelAppointment(
                $appointment,
                $customer,
                $request->input('reason')
            );

            return $this->sendRes(
                __('appointment.cancelled_successfully'),
                true,
                [],
                [],
                200
            );
        } catch (\Exception $e) {
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}
