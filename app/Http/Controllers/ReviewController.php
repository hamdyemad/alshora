<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Lawyer;
use App\Models\Customer;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:reviews.view')->only(['index', 'show']);
        $this->middleware('can:reviews.approve')->only(['approve']);
        $this->middleware('can:reviews.reject')->only(['reject']);
        $this->middleware('can:reviews.delete')->only(['destroy']);
    }

    /**
     * Display all reviews (admin view)
     */
    public function index($lawyerId = null)
    {
        if ($lawyerId) {
            $lawyer = Lawyer::findOrFail($lawyerId);
            $reviews = $lawyer->reviews()->approved()->latest()->paginate(10);
            $averageRating = Review::getAverageRating($lawyerId);
            $totalReviews = Review::getTotalReviews($lawyerId);

            return view('pages.reviews.index', compact('lawyer', 'reviews', 'averageRating', 'totalReviews'));
        }

        // Admin view - all reviews
        $reviews = Review::with('lawyer', 'customer')->latest()->paginate(10);
        return view('pages.reviews.admin-index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review
     */
    public function create($lawyerId = null)
    {
        if (!$lawyerId) {
            return redirect()->back()->with('error', trans('reviews.lawyer_required'));
        }

        $lawyer = Lawyer::findOrFail($lawyerId);
        return view('pages.reviews.create', compact('lawyer'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, $lawyerId = null)
    {
        if (!$lawyerId) {
            return redirect()->back()->with('error', trans('reviews.lawyer_required'));
        }

        $lawyer = Lawyer::findOrFail($lawyerId);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Get authenticated customer
        $customerId = auth()->guard('customer')->id();
        if (!$customerId) {
            return redirect()->back()->with('error', trans('reviews.login_required'));
        }

        // Check if customer already reviewed this lawyer
        $existingReview = Review::where('lawyer_id', $lawyerId)
            ->where('customer_id', $customerId)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', trans('reviews.already_reviewed'));
        }

        Review::create([
            'lawyer_id' => $lawyerId,
            'customer_id' => $customerId,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
            'approved' => false,
        ]);

        return redirect()->route('lawyers.show', $lawyerId)
            ->with('success', trans('reviews.review_submitted'));
    }

    /**
     * Display a specific review
     */
    public function show($lawyerId = null, $reviewId = null)
    {
        // Handle both show($reviewId) and show($lawyerId, $reviewId)
        if ($reviewId === null && $lawyerId !== null) {
            $reviewId = $lawyerId;
            $review = Review::findOrFail($reviewId);
            $lawyer = $review->lawyer;
        } else {
            $lawyer = Lawyer::findOrFail($lawyerId);
            $review = Review::where('lawyer_id', $lawyerId)->findOrFail($reviewId);
        }

        return view('pages.reviews.show', compact('lawyer', 'review'));
    }

    /**
     * Approve a review
     */
    public function approve($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->update(['approved' => true]);

        return redirect()->route('admin.reviews.show', $review->id)
            ->with('success', trans('reviews.review_approved'));
    }

    /**
     * Reject a review
     */
    public function reject($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->update(['approved' => false]);

        return redirect()->route('admin.reviews.show', $review->id)
            ->with('success', trans('reviews.review_rejected'));
    }

    /**
     * Delete a review
     */
    public function destroy($lawyerId = null, $reviewId = null)
    {
        // Handle both destroy($reviewId) and destroy($lawyerId, $reviewId)
        if ($reviewId === null && $lawyerId !== null) {
            $reviewId = $lawyerId;
            $review = Review::findOrFail($reviewId);
        } else {
            $lawyer = Lawyer::findOrFail($lawyerId);
            $review = Review::where('lawyer_id', $lawyerId)->findOrFail($reviewId);
        }

        $review->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => trans('common.deleted_successfully'),
            ]);
        }

        return redirect()->route('reviews.index')
            ->with('success', trans('reviews.review_deleted'));
    }
}
