<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HostingSlotReservationController;


Route::group(['prefix' => 'hosting', 'middleware' => ['auth:sanctum', 'lawyer']], function () {
    // Public routes - get available slots
    Route::get('/available-slots', [HostingSlotReservationController::class, 'getAvailableSlots']);
    Route::post('/request-slot', [HostingSlotReservationController::class, 'requestSlot']);
    Route::get('/my-reservations', [HostingSlotReservationController::class, 'getMyReservations']);
});
