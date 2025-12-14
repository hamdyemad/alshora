<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\DraftingContractController;

/*
|--------------------------------------------------------------------------
| Drafting Contracts API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('drafting-contracts')->group(function () {
    Route::get('/', [DraftingContractController::class, 'index']);
    Route::get('/{id}', [DraftingContractController::class, 'show']);
});
