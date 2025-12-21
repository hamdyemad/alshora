<?php

use App\Http\Controllers\Api\v1\AgendaController;
use App\Http\Controllers\Api\v1\PreparerAgendaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('agendas', AgendaController::class);
    Route::apiResource('preparer-agendas', PreparerAgendaController::class);
});
