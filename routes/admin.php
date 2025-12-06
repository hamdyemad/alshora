<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaginationController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AdminManagement\RoleController;
use App\Http\Controllers\AreaSettings\CountryController;
use App\Http\Controllers\AreaSettings\CityController;
use App\Http\Controllers\AreaSettings\RegionController;
use App\Http\Controllers\AreaSettings\SubRegionController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\LawyerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SectionOfLawController;
use App\Http\Controllers\InstructionController;
use App\Http\Controllers\BranchOfLawController;
use App\Http\Controllers\SubscriptionController;

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

        // Area Settings
        Route::prefix('area-settings')->name('area-settings.')->group(function() {
            Route::resource('countries', CountryController::class);
            Route::resource('cities', CityController::class);
            Route::resource('regions', RegionController::class);
            Route::resource('subregions', SubRegionController::class);
        });

        // Activities
        Route::resource('activities', ActivityController::class);

        // Lawyers
        Route::resource('lawyers', LawyerController::class);
        Route::post('lawyers/{lawyer}/update-office-hours', [LawyerController::class, 'updateOfficeHours'])->name('lawyers.update-office-hours');
        Route::post('lawyers/{lawyer}/update-specializations', [LawyerController::class, 'updateSpecializations'])->name('lawyers.update-specializations');
        Route::post('lawyers/{lawyer}/toggle-ads', [LawyerController::class, 'toggleAds'])->name('lawyers.toggle-ads');
        Route::post('lawyers/{lawyer}/toggle-block', [LawyerController::class, 'toggleBlock'])->name('lawyers.toggle-block');
        Route::post('lawyers/{lawyer}/renew-subscription', [LawyerController::class, 'renewSubscription'])->name('lawyers.renew-subscription');

        // Customers
        Route::resource('customers', CustomerController::class);
        Route::post('customers/{customer}/toggle-active', [CustomerController::class, 'toggleActive'])->name('customers.toggle-active');

        // Subscriptions
        Route::resource('subscriptions', SubscriptionController::class);

        // News
        Route::resource('news', NewsController::class);

        // Sections of Laws
        Route::resource('sections-of-laws', SectionOfLawController::class);

        // Instructions
        Route::resource('instructions', InstructionController::class);

        // Branches of Laws
        Route::resource('branches-of-laws', BranchOfLawController::class);
    });
});






