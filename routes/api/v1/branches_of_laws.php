<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\BranchOfLawController;

/*
|--------------------------------------------------------------------------
| Branches of Laws API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('branches-of-laws')->group(function () {
    Route::get('/', [BranchOfLawController::class, 'index']);
    Route::get('/{id}', [BranchOfLawController::class, 'show']);
});
