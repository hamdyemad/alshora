<?php

use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\v1\AppointmentController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'customer', 'middleware' => ['auth:sanctum']], function() {
    // Appointment routes
    Route::post('appointments/reserve', [AppointmentController::class, 'reserve']);
    Route::get('appointments', [AppointmentController::class, 'myAppointments']);
    Route::post('appointments/{appointmentId}/cancel', [AppointmentController::class, 'cancel']);

    Route::prefix('{lawyer_id}/reviews')->group(function () {
        // Create a new review
        Route::post('/', [ReviewApiController::class, 'store'])->name('api.reviews.store');
    });


});
