<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReviewApiController;


Route::prefix('reviews/{lawyer_id}')->group(function () {
    // Get all reviews for a lawyer
    Route::get('/', [ReviewApiController::class, 'index'])->name('api.reviews.index');
    // Get a specific review
    Route::get('{review_id}', [ReviewApiController::class, 'show'])->name('api.reviews.show');
    // Get rating statistics for a lawyer
    Route::get('statistics/ratings', [ReviewApiController::class, 'statistics'])->name('api.reviews.statistics');
});
