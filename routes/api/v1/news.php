<?php

use App\Http\Controllers\Api\v1\NewsController;
use Illuminate\Support\Facades\Route;

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index']);
    Route::get('/{id}', [NewsController::class, 'show']);
});
