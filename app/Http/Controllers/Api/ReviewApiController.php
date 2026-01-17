<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Lawyer;
use App\Http\Resources\ReviewResource;
use App\Traits\Res;
use Illuminate\Http\Request;

class ReviewApiController extends Controller
{
    use Res;
    
    /**
     * Get reviews for the authenticated user
     * - If customer: get reviews they wrote
     * - If lawyer: get reviews they received
     */
    public function myReviews(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is a customer
        if ($user->customer) {
            $reviews = Review::where('customer_id', $user->customer->id)
                ->with(['lawyer.user', 'lawyer.profile_image'])
                ->latest()
                ->paginate(10);
            
            $data = [
                'type' => 'customer_reviews',
                'items' => ReviewResource::collection($reviews),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                    'from' => $reviews->firstItem(),
                    'to' => $reviews->lastItem(),
                ]
            ];
            
            return $this->sendRes(
                __('validation.success'),
                true,
                $data,
                []
            );
        }
        
        // Check if user is a lawyer
        if ($user->lawyer) {
            $reviews = Review::where('lawyer_id', $user->lawyer->id)
                ->approved()
                ->with(['customer.user', 'customer.logo'])
                ->latest()
                ->paginate(10);
            
            $averageRating = Review::getAverageRating($user->lawyer->id);
            $totalReviews = Review::getTotalReviews($user->lawyer->id);
            
            $data = [
                'type' => 'lawyer_reviews',
                'average_rating' => round($averageRating, 2),
                'total_reviews' => $totalReviews,
                'items' => ReviewResource::collection($reviews),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                    'from' => $reviews->firstItem(),
                    'to' => $reviews->lastItem(),
                ]
            ];
            
            return $this->sendRes(
                __('validation.success'),
                true,
                $data,
                []
            );
        }
        
        // User is neither customer nor lawyer
        return $this->sendRes(
            trans('reviews.no_profile_found'),
            false,
            [],
            [],
            403
        );
    }
    
    /**
     * Get all reviews for a lawyer
     */
    public function index($lawyerId)
    {
        $lawyer = Lawyer::findOrFail($lawyerId);
        $reviews = $lawyer->reviews()->approved()->latest()->paginate(10);
        $averageRating = Review::getAverageRating($lawyerId);
        $totalReviews = Review::getTotalReviews($lawyerId);

        $data = [
            'average_rating' => round($averageRating, 2),
            'total_reviews' => $totalReviews,
            'items' => ReviewResource::collection($reviews),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
                'from' => $reviews->firstItem(),
                'to' => $reviews->lastItem(),
            ]
        ];
        return $this->sendRes(
            __('validation.success'),
            true,
            $data,
            []
        );
    }

    /**
     * Create a new review
     */
    public function store(Request $request, $lawyerId)
    {
        $lawyer = Lawyer::findOrFail($lawyerId);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        
        // Check if user has a customer profile
        if (!$user->customer) {
            return $this->sendRes(
                trans('reviews.customer_profile_required'),
                false,
                [],
                [],
                403
            );
        }

        // Check if customer already reviewed this lawyer
        $existingReview = Review::where('lawyer_id', $lawyerId)
            ->where('customer_id', $user->customer->id)
            ->first();

        if ($existingReview) {
            return $this->sendRes(
                trans('reviews.already_reviewed'),
                false,
                [],
                [],
                422
            );
        }

        $review = Review::create([
            'lawyer_id' => $lawyerId,
            'customer_id' => $user->customer->id,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
            'approved' => false,
        ]);

        return $this->sendRes(
            trans('reviews.review_submitted'),
            true,
            new ReviewResource($review),
            [],
            201
        );
    }

    /**
     * Get a specific review
     */
    public function show($lawyerId, $reviewId)
    {
        $lawyer = Lawyer::findOrFail($lawyerId);
        $review = Review::where('lawyer_id', $lawyerId)->findOrFail($reviewId);

        return $this->sendRes(
            __('validation.success'),
            true,
            new ReviewResource($review)
        );
    }

    /**
     * Update a review
     */
    public function update(Request $request, $lawyerId, $reviewId)
    {
        $lawyer = Lawyer::findOrFail($lawyerId);
        $review = Review::where('lawyer_id', $lawyerId)->findOrFail($reviewId);

        $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($request->has('rating')) {
            $review->rating = $request->input('rating');
        }

        if ($request->has('comment')) {
            $review->comment = $request->input('comment');
        }

        $review->save();

        return $this->sendRes(
            trans('common.updated_successfully'),
            true,
            new ReviewResource($review)
        );
    }

    /**
     * Delete a review
     */
    public function destroy($lawyerId, $reviewId)
    {
        $lawyer = Lawyer::findOrFail($lawyerId);
        $review = Review::where('lawyer_id', $lawyerId)->findOrFail($reviewId);

        $review->delete();

        return $this->sendRes(
            trans('common.deleted_successfully'),
            true,
            [],
            [],
            200
        );
    }

    /**
     * Get lawyer rating statistics
     */
    public function statistics($lawyerId)
    {
        $lawyer = Lawyer::findOrFail($lawyerId);

        $ratingDistribution = Review::where('lawyer_id', $lawyerId)
            ->approved()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $averageRating = Review::getAverageRating($lawyerId);
        $totalReviews = Review::getTotalReviews($lawyerId);

        return $this->sendRes(
            __('validation.success'),
            true,
            [
                'lawyer_id' => $lawyerId,
                'average_rating' => round($averageRating ?? 0, 2),
                'total_reviews' => $totalReviews,
                'rating_distribution' => [
                    '5_stars' => $ratingDistribution[5] ?? 0,
                    '4_stars' => $ratingDistribution[4] ?? 0,
                    '3_stars' => $ratingDistribution[3] ?? 0,
                    '2_stars' => $ratingDistribution[2] ?? 0,
                    '1_star' => $ratingDistribution[1] ?? 0,
                ]
            ]
        );
    }
}
