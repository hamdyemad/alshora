<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\DraftingLawsuitController;

/*
|--------------------------------------------------------------------------
| Drafting Lawsuits API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('drafting-lawsuits')->group(function () {
    Route::get('/', [DraftingLawsuitController::class, 'index']);
    Route::get('/{id}', [DraftingLawsuitController::class, 'show']);
});
