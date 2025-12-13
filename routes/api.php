<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API v1 routes
Route::prefix('v1')->group(function () {
    require __DIR__ . '/api/v1/reviews.php';
});

// Notification routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/notifications/fcm-token', [NotificationController::class, 'updateFcmToken']);
    Route::delete('/notifications/fcm-token', [NotificationController::class, 'removeFcmToken']);
    Route::get('/notifications/settings', [NotificationController::class, 'getNotificationSettings']);
});
