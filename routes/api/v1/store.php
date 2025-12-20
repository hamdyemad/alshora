<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\StoreCategoryController;
use App\Http\Controllers\Api\v1\StoreProductController;
use App\Http\Controllers\Api\v1\StoreOrderController;
use App\Http\Controllers\Api\v1\StoreCartController;

/*
|--------------------------------------------------------------------------
| Store API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('store')->group(function () {
    // Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [StoreCategoryController::class, 'index']);
        Route::get('/{id}', [StoreCategoryController::class, 'show']);
    });

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [StoreProductController::class, 'index']);
        Route::get('/{id}', [StoreProductController::class, 'show']);
    });

    // Orders
    Route::prefix('orders')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [StoreOrderController::class, 'index']);
        Route::post('/', [StoreOrderController::class, 'store']);
        Route::get('/{id}', [StoreOrderController::class, 'show']);
    });

    // Cart
    Route::prefix('cart')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [StoreCartController::class, 'index']);
        Route::post('/', [StoreCartController::class, 'store']);
        Route::put('/{id}', [StoreCartController::class, 'update']);
        Route::delete('/{id}', [StoreCartController::class, 'destroy']);
        Route::delete('/', [StoreCartController::class, 'clear']);
    });
});

