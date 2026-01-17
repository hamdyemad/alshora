<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReviewApiController;

// Authenticated user's reviews - requires token
Route::middleware('auth:sanctum')->group(function () {
    // Get my reviews (for authenticated user - customer or lawyer)
    Route::get('my-reviews', [ReviewApiController::class, 'myReviews'])->name('api.reviews.my');
});

// Public routes - accessible with or without authentication
// These routes will work without token, but if token is provided, user will be authenticated
Route::prefix('reviews/{lawyer_id}')->group(function () {
    // Get all reviews for a lawyer (public)
    Route::get('/', [ReviewApiController::class, 'index'])->name('api.reviews.index');
    
    // Get a specific review (public)
    Route::get('{review_id}', [ReviewApiController::class, 'show'])->name('api.reviews.show');
    
    // Get rating statistics for a lawyer (public)
    Route::get('statistics/ratings', [ReviewApiController::class, 'statistics'])->name('api.reviews.statistics');
});
