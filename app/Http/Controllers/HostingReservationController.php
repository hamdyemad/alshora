<?php

namespace App\Http\Controllers;

use App\Models\HostingSlotReservation;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostingReservationController extends Controller
{
    /**
     * Display a listing of pending hosting reservations
     */
    public function index(Request $request)
    {
        $query = HostingSlotReservation::with('lawyer.user', 'hostingTime');

        // Filter by lawyer name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('lawyer', function($subQ) use ($search) {
                    $subQ->whereHas('translations', function($subSubQ) use ($search) {
                        $subSubQ->where('lang_value', 'like', "%{$search}%");
                    });
                })
                ->orWhereHas('lawyer.user', function ($subQ) use ($search) {
                    $subQ->where('email', 'like', "%{$search}%");
                });
            });
        }

        // Filter by day
        if ($request->filled('day')) {
            $day = $request->input('day');
            $query->whereHas('hostingTime', function ($q) use ($day) {
                $q->where('day', $day);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by created date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $reservations = $query->latest()->paginate(15)->appends($request->query());

        return view('pages.hosting.reservations.index', compact('reservations'));
    }

    /**
     * Display the specified reservation
     */
    public function show(HostingSlotReservation $reservation)
    {
        $reservation->load('lawyer.user', 'hostingTime', 'approvedBy');
        return view('pages.hosting.reservations.show', compact('reservation'));
    }

    /**
     * Approve a hosting slot reservation
     */
    public function approve(Request $request, HostingSlotReservation $reservation)
    {
        try {
            $validated = $request->validate([
                'admin_notes' => 'nullable|string|max:500',
            ]);

            $reservation->load('lawyer.user', 'hostingTime');
            $reservation->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'admin_notes' => $validated['admin_notes'] ?? null,
            ]);

            // Send Firebase notification to lawyer
            $firebaseService = new FirebaseNotificationService();
            $firebaseService->sendHostingSlotApprovedNotification($reservation->lawyer, $reservation);

            return redirect()->back()->with('success', __('hosting.reservation_approved_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error approving reservation: ' . $e->getMessage());
        }
    }

    /**
     * Reject a hosting slot reservation
     */
    public function reject(Request $request, HostingSlotReservation $reservation)
    {
        try {
            $validated = $request->validate([
                'admin_notes' => 'required|string|max:500',
            ]);

            $reservation->load('lawyer.user', 'hostingTime');
            $reservation->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'admin_notes' => $validated['admin_notes'],
            ]);

            // Send Firebase notification to lawyer
            $firebaseService = new FirebaseNotificationService();
            $firebaseService->sendHostingSlotRejectedNotification($reservation->lawyer, $reservation);

            return redirect()->back()->with('success', __('hosting.reservation_rejected_successfully'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->with('error', 'Validation failed');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error rejecting reservation: ' . $e->getMessage());
        }
    }
}
