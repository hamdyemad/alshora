<?php

use App\Http\Controllers\Api\v1\Area\CityController;
use App\Http\Controllers\Api\v1\Area\CountryController;
use App\Http\Controllers\Api\v1\Area\RegionController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'area'], function() {
    Route::get('cities', [CityController::class, 'index']);

    Route::group(['prefix' => 'regions'], function() {
        Route::get('by-city/{id}', [RegionController::class, 'getRegionsByCity']);
    });

    Route::group(['prefix' => 'countries'], function() {
        Route::get('/', [CountryController::class, 'index']);
    });

});

