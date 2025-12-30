<?php

use App\Http\Controllers\Api\v1\InstructionController;
use Illuminate\Support\Facades\Route;

Route::prefix('instructions')->group(function () {
    Route::get('/', [InstructionController::class, 'index']);
    Route::get('/{id}', [InstructionController::class, 'show']);
});
