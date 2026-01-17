<?php

use App\Http\Controllers\Api\v1\Lawyer\AuthController;
use App\Http\Controllers\Api\v1\Lawyer\LawyerController;
use App\Http\Controllers\Api\v1\Lawyer\OfficeWorkController;
use App\Http\Controllers\Api\v1\RegisterationGradeController;
use App\Http\Controllers\Api\v1\SectionOfLawController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'lawyer'], function() {
    Route::get('/', [LawyerController::class, 'index']);
    Route::get('registeration_grades', [RegisterationGradeController::class, 'index']);
    Route::get('section_of_laws', [SectionOfLawController::class, 'index']);
    Route::post('office-work', [OfficeWorkController::class, 'updateOfficeWork'])->middleware('auth:sanctum', 'lawyer');
    Route::get('appointments', [\App\Http\Controllers\Api\v1\Lawyer\AppointmentController::class, 'index'])->middleware('auth:sanctum', 'lawyer');
    Route::put('appointments/{id}/status', [\App\Http\Controllers\Api\v1\Lawyer\AppointmentController::class, 'updateStatus'])->middleware('auth:sanctum', 'lawyer');
    Route::post('appointments/{id}/approve', [\App\Http\Controllers\Api\v1\Lawyer\AppointmentController::class, 'approve'])->middleware('auth:sanctum', 'lawyer');
    Route::post('appointments/{id}/reject', [\App\Http\Controllers\Api\v1\Lawyer\AppointmentController::class, 'reject'])->middleware('auth:sanctum', 'lawyer');
    Route::post('appointments/{id}/complete', [\App\Http\Controllers\Api\v1\Lawyer\AppointmentController::class, 'complete'])->middleware('auth:sanctum', 'lawyer');
    
    // Accounting routes
    Route::middleware(['auth:sanctum', 'lawyer'])->prefix('accounting')->group(function() {
        Route::get('stats', [\App\Http\Controllers\Api\v1\Lawyer\AccountingController::class, 'stats']);
        Route::get('transactions', [\App\Http\Controllers\Api\v1\Lawyer\AccountingController::class, 'index']);
        Route::post('transactions', [\App\Http\Controllers\Api\v1\Lawyer\AccountingController::class, 'store']);
        Route::get('transactions/{id}', [\App\Http\Controllers\Api\v1\Lawyer\AccountingController::class, 'show']);
        Route::put('transactions/{id}', [\App\Http\Controllers\Api\v1\Lawyer\AccountingController::class, 'update']);
        Route::delete('transactions/{id}', [\App\Http\Controllers\Api\v1\Lawyer\AccountingController::class, 'destroy']);
        Route::get('by-category', [\App\Http\Controllers\Api\v1\Lawyer\AccountingController::class, 'byCategory']);
        Route::get('chart-data', [\App\Http\Controllers\Api\v1\Lawyer\AccountingController::class, 'chartData']);
        Route::get('categories', [\App\Http\Controllers\Api\v1\Lawyer\AccountingController::class, 'categories']);
    });
    
    Route::get('/{lawyer_id}', [LawyerController::class, 'show']);
});

