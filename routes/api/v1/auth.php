<?php

use App\Http\Controllers\Api\v1\AuthController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function() {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => ['auth:sanctum']], function() {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('profile', [AuthController::class, 'profile_update']);
    });

    Route::group(['prefix' => 'password'], function() {
        Route::post('forget', [AuthController::class, 'forgetPassword']);
        Route::post('reset', [AuthController::class, 'resetPassword']);
        Route::post('change', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
    });
});
