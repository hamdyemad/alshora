<?php

namespace App\Interfaces;

use App\Models\HostingSlotReservation;
use Illuminate\Pagination\Paginator;

interface HostingSlotReservationRepositoryInterface
{
    /**
     * Get all available hosting time slots
     */
    public function getAvailableSlots(): array;

    /**
     * Request a hosting slot
     */
    public function requestSlot(int $lawyerId, int $hostingTimeId, ?string $reason = null): HostingSlotReservation;

    /**
     * Get lawyer's reservations
     */
    public function getLawyerReservations(int $lawyerId): array;

    /**
     * Get all pending reservations
     */
    public function getPendingReservations(): array;

    /**
     * Check if lawyer already has a reservation for this slot
     */
    public function hasExistingReservation(int $lawyerId, int $hostingTimeId): bool;

    /**
     * Approve a reservation
     */
    public function approveReservation(HostingSlotReservation $reservation, int $approvedBy, ?string $adminNotes = null): HostingSlotReservation;

    /**
     * Reject a reservation
     */
    public function rejectReservation(HostingSlotReservation $reservation, int $approvedBy, string $adminNotes): HostingSlotReservation;

    /**
     * Find reservation by ID
     */
    public function findById(int $id): ?HostingSlotReservation;
}
