<?php

use App\Http\Controllers\Api\v1\Lawyer\AuthController;
use App\Http\Controllers\Api\v1\Lawyer\LawyerController;
use App\Http\Controllers\Api\v1\Lawyer\OfficeWorkController;
use App\Http\Controllers\Api\v1\RegisterationGradeController;
use App\Http\Controllers\Api\v1\SectionOfLawController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'lawyer'], function() {
    Route::get('/', [LawyerController::class, 'index'])->middleware('auth:sanctum');
    Route::get('registeration_grades', [RegisterationGradeController::class, 'index'])->middleware('auth:sanctum');
    Route::get('section_of_laws', [SectionOfLawController::class, 'index'])->middleware('auth:sanctum');
    Route::post('office-work', [OfficeWorkController::class, 'updateOfficeWork'])->middleware('auth:sanctum', 'lawyer');
    Route::get('appointments', [\App\Http\Controllers\Api\v1\Lawyer\AppointmentController::class, 'index'])->middleware('auth:sanctum', 'lawyer');
    Route::put('appointments/{id}/status', [\App\Http\Controllers\Api\v1\Lawyer\AppointmentController::class, 'updateStatus'])->middleware('auth:sanctum', 'lawyer');
    Route::get('/{lawyer_id}', [LawyerController::class, 'show'])->middleware('auth:sanctum');
});

