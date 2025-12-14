<?php

namespace App\Services;

use App\Interfaces\HostingSlotReservationRepositoryInterface;
use App\Models\HostingSlotReservation;
use Illuminate\Support\Facades\Log;

class HostingSlotReservationService
{
    public function __construct(
        private HostingSlotReservationRepositoryInterface $repository
    ) {
    }

    /**
     * Get all available hosting slots
     */
    public function getAvailableSlots(): array
    {
        try {
            return $this->repository->getAvailableSlots();
        } catch (\Exception $e) {
            Log::error('Error retrieving available hosting slots', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Request a hosting slot for a lawyer
     */
    public function requestSlot(int $lawyerId, int $hostingTimeId, ?string $reason = null): HostingSlotReservation
    {
        $reservation = $this->repository->requestSlot($lawyerId, $hostingTimeId, $reason);
        return $reservation->load('lawyer', 'hostingTime');
    }

    /**
     * Get lawyer's reservations
     */
    public function getLawyerReservations(int $lawyerId): array
    {
        try {
            return $this->repository->getLawyerReservations($lawyerId);
        } catch (\Exception $e) {
            Log::error('Error retrieving lawyer reservations', [
                'lawyer_id' => $lawyerId,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get all pending reservations (Admin only)
     */
    public function getPendingReservations(): array
    {
        try {
            return $this->repository->getPendingReservations();
        } catch (\Exception $e) {
            Log::error('Error retrieving pending reservations', [
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Approve a hosting slot reservation
     */
    public function approveReservation(HostingSlotReservation $reservation, int $approvedBy, ?string $adminNotes = null): HostingSlotReservation
    {
        try {
            $reservation = $this->repository->approveReservation($reservation, $approvedBy, $adminNotes);

            Log::info('Hosting slot reservation approved', [
                'reservation_id' => $reservation->id,
                'lawyer_id' => $reservation->lawyer_id,
                'approved_by' => $approvedBy,
            ]);

            return $reservation;
        } catch (\Exception $e) {
            Log::error('Error approving hosting slot reservation', [
                'reservation_id' => $reservation->id,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Reject a hosting slot reservation
     */
    public function rejectReservation(HostingSlotReservation $reservation, int $approvedBy, string $adminNotes): HostingSlotReservation
    {
        try {
            $reservation = $this->repository->rejectReservation($reservation, $approvedBy, $adminNotes);

            Log::info('Hosting slot reservation rejected', [
                'reservation_id' => $reservation->id,
                'lawyer_id' => $reservation->lawyer_id,
                'approved_by' => $approvedBy,
            ]);

            return $reservation;
        } catch (\Exception $e) {
            Log::error('Error rejecting hosting slot reservation', [
                'reservation_id' => $reservation->id,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
