<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestHostingSlotRequest;
use App\Http\Requests\ApproveHostingReservationRequest;
use App\Http\Requests\RejectHostingReservationRequest;
use App\Http\Resources\HostingSlotReservationResource;
use App\Models\HostingSlotReservation;
use App\Services\HostingSlotReservationService;
use App\Services\FirebaseNotificationService;
use App\Traits\Res;
use Illuminate\Support\Facades\Auth;

class HostingSlotReservationController extends Controller
{
    use Res;

    public function __construct(
        private HostingSlotReservationService $service
    ) {
    }

    /**
     * Get available hosting days and time slots
     */
    public function getAvailableSlots()
    {
        $data = $this->service->getAvailableSlots();

        return $this->sendRes(
            __('hosting.available_slots_retrieved'),
            true,
            $data
        );
    }

    /**
     * Request a hosting slot
     */
    public function requestSlot(RequestHostingSlotRequest $request)
    {
        $lawyer = $request->user()->lawyer;

        $reservation = $this->service->requestSlot(
            $lawyer->id,
            $request->hosting_time_id,
            $request->reason
        );

        return $this->sendRes(
            __('hosting.slot_requested_successfully'),
            true,
            new HostingSlotReservationResource($reservation),
            [],
            201
        );
    }

    /**
     * Get lawyer's reservations
     */
    public function getMyReservations()
    {
        $per_page = request()->input('per_page', 10);
        $lawyer = Auth::user()->lawyer;
        $reservations = HostingSlotReservation::where('lawyer_id', $lawyer->id)
            ->with('hostingTime')
            ->orderBy('created_at', 'desc');

        $reservations = ($per_page > 0) ? $reservations->paginate($per_page) : $reservations->get();

        if ($per_page > 0) {
            $data = [
                'items' => HostingSlotReservationResource::collection($reservations),
                'pagination' => [
                    'current_page' => $reservations->currentPage(),
                    'last_page' => $reservations->lastPage(),
                    'per_page' => $reservations->perPage(),
                    'total' => $reservations->total(),
                    'from' => $reservations->firstItem(),
                    'to' => $reservations->lastItem(),
                ]
            ];
            return $this->sendRes(__('hosting.reservations_retrieved'), true, $data);
        }
        $data = [
            'items' => $reservations
        ];
        return $this->sendRes(__('hosting.reservations_retrieved'), true, $data);

    }

    /**
     * Approve a hosting slot reservation
     */
    public function approveReservation(ApproveHostingReservationRequest $request, HostingSlotReservation $reservation)
    {
        $reservation = $this->service->approveReservation(
            $reservation,
            Auth::id(),
            $request->admin_notes
        );

        // Send Firebase notification to lawyer
        $firebaseService = new FirebaseNotificationService();
        $firebaseService->sendHostingSlotApprovedNotification($reservation->lawyer, $reservation);

        return $this->sendRes(
            new HostingSlotReservationResource($reservation),
            true,
            __('hosting.reservation_approved_successfully')
        );
    }

    /**
     * Reject a hosting slot reservation
     */
    public function rejectReservation(RejectHostingReservationRequest $request, HostingSlotReservation $reservation)
    {
        $reservation = $this->service->rejectReservation(
            $reservation,
            Auth::id(),
            $request->admin_notes
        );

        // Send Firebase notification to lawyer
        $firebaseService = new FirebaseNotificationService();
        $firebaseService->sendHostingSlotRejectedNotification($reservation->lawyer, $reservation);

        return $this->sendRes(
            new HostingSlotReservationResource($reservation),
            true,
            __('hosting.reservation_rejected_successfully')
        );
    }

}
