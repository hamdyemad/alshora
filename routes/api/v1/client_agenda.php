<?php

use App\Http\Controllers\Api\v1\ClientAgendaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('client-agendas', ClientAgendaController::class);
});
