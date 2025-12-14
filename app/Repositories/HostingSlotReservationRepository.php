<?php

namespace App\Repositories;

use App\Interfaces\HostingSlotReservationRepositoryInterface;
use App\Models\HostingSlotReservation;
use App\Models\HostingTime;

class HostingSlotReservationRepository implements HostingSlotReservationRepositoryInterface
{
    /**
     * Get all available hosting time slots
     */
    public function getAvailableSlots(): array
    {
        $days = HostingTime::getDays();
        $hostingTimes = HostingTime::where('is_active', true)
            ->orderByRaw("FIELD(day, 'saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday')")
            ->orderBy('from_time')
            ->get()
            ->groupBy('day')
            ->map(function ($slots) {
                return $slots->map(function ($slot) {
                    return [
                        'id' => $slot->id,
                        'from_time' => $slot->from_time,
                        'to_time' => $slot->to_time,
                        'is_available' => true,
                    ];
                });
            });

        return [
            'days' => $days,
            'hosting_times' => $hostingTimes,
        ];
    }

    /**
     * Request a hosting slot
     */
    public function requestSlot(int $lawyerId, int $hostingTimeId, ?string $reason = null): HostingSlotReservation
    {
        return HostingSlotReservation::create([
            'lawyer_id' => $lawyerId,
            'hosting_time_id' => $hostingTimeId,
            'reason' => $reason,
            'status' => 'pending',
        ]);
    }

    /**
     * Get lawyer's reservations
     */
    public function getLawyerReservations(int $lawyerId): array
    {
        return HostingSlotReservation::where('lawyer_id', $lawyerId)
            ->with('hostingTime')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get all pending reservations
     */
    public function getPendingReservations(): array
    {
        return HostingSlotReservation::pending()
            ->with('lawyer.user', 'hostingTime')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * Check if lawyer already has a reservation for this slot
     */
    public function hasExistingReservation(int $lawyerId, int $hostingTimeId): bool
    {
        return HostingSlotReservation::where('lawyer_id', $lawyerId)
            ->where('hosting_time_id', $hostingTimeId)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }

    /**
     * Approve a reservation
     */
    public function approveReservation(HostingSlotReservation $reservation, int $approvedBy, ?string $adminNotes = null): HostingSlotReservation
    {
        $reservation->update([
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
            'admin_notes' => $adminNotes,
        ]);

        return $reservation->fresh(['lawyer', 'hostingTime', 'approvedBy']);
    }

    /**
     * Reject a reservation
     */
    public function rejectReservation(HostingSlotReservation $reservation, int $approvedBy, string $adminNotes): HostingSlotReservation
    {
        $reservation->update([
            'status' => 'rejected',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
            'admin_notes' => $adminNotes,
        ]);

        return $reservation->fresh(['lawyer', 'hostingTime', 'approvedBy']);
    }

    /**
     * Find reservation by ID
     */
    public function findById(int $id): ?HostingSlotReservation
    {
        return HostingSlotReservation::with('lawyer.user', 'hostingTime', 'approvedBy')->find($id);
    }
}
