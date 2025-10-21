<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaginationController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AdminManagement\RoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(
[
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'setLocaleFromUrl' ]
], function(){

    Route::redirect('/admin', 'admin/dashboard');
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Admin Management
        Route::prefix('admin-management')->name('admin-management.')->group(function() {
            Route::resource('roles', RoleController::class);
        });

        // Vendors
        Route::resource('vendors', VendorController::class);
    });
});






