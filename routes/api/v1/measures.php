<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\MeasureController;

/*
|--------------------------------------------------------------------------
| Measures API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('measures')->group(function () {
    Route::get('/', [MeasureController::class, 'index']);
    Route::get('/{id}', [MeasureController::class, 'show']);
});

