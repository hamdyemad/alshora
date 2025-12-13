<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService $reservationService
    ) {}

    /**
     * Display reservations/appointments dashboard
     */
    public function index(Request $request)
    {
        try {
            // Validate and prepare filters
            $filters = $this->reservationService->validateFilters($request->all());

            // Get appointments with filters
            $appointments = $this->reservationService->getAllAppointments($filters, 15);

            // Get filter options
            $filterOptions = $this->reservationService->getFilterOptions();

            return view('pages.reservations.index', [
                'appointments' => $appointments,
                'lawyers' => $filterOptions['lawyers'],
                'statuses' => $filterOptions['statuses']
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error loading reservations: ' . $e->getMessage());
        }
    }

    /**
     * Show appointment details
     */
    public function show($id)
    {
        try {
            $appointment = $this->reservationService->getAppointmentById($id);

            if (!$appointment) {
                return redirect()->route('admin.reservations.index')
                    ->with('error', 'Appointment not found');
            }

            return view('pages.reservations.show', compact('appointment'));
        } catch (\Exception $e) {
            return redirect()->route('admin.reservations.index')
                ->with('error', 'Error loading appointment: ' . $e->getMessage());
        }
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        try {
            $success = $this->reservationService->updateAppointmentStatus($id, $request->status);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found or could not be updated'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Appointment status updated successfully'
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating appointment status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics (API endpoint)
     */
    public function getDashboardStats()
    {
        try {
            $stats = $this->reservationService->getDashboardStats();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading dashboard statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
