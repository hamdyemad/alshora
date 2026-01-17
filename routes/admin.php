<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaginationController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AdminManagement\RoleController;
use App\Http\Controllers\AdminManagement\AdminController;
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
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DraftingContractController;
use App\Http\Controllers\DraftingLawsuitController;
use App\Http\Controllers\MeasureController;
use App\Http\Controllers\StoreCategoryController;
use App\Http\Controllers\StoreProductController;
use App\Http\Controllers\StoreOrderController;
use App\Http\Controllers\LawController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HostingController;
use App\Http\Controllers\HostingReservationController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\PreparerAgendaController;
use App\Http\Controllers\SupportMessageController;
use App\Http\Controllers\AccountingController;

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
            Route::resource('admins', AdminController::class);
            Route::post('admins/{admin}/toggle-blocked', [AdminController::class, 'toggleBlocked'])->name('admins.toggle-blocked');
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
        Route::post('lawyers/{lawyer}/toggle-featured', [LawyerController::class, 'toggleFeatured'])->name('lawyers.toggle-featured');

        // Customers
        Route::resource('customers', CustomerController::class);
        Route::post('customers/{customer}/toggle-active', [CustomerController::class, 'toggleActive'])->name('customers.toggle-active');

        // Subscriptions
        Route::resource('subscriptions', SubscriptionController::class);

        // News
        Route::resource('news', NewsController::class);

        // Support Messages
        Route::resource('support-messages', SupportMessageController::class)->only(['index', 'show', 'destroy']);
        Route::post('support-messages/{id}/status', [SupportMessageController::class, 'updateStatus'])->name('support-messages.update-status');

        // Accounting
        Route::prefix('accounting')->name('accounting.')->group(function() {
            Route::get('/', [AccountingController::class, 'index'])->name('index');
            Route::get('lawyers/{lawyer}', [AccountingController::class, 'show'])->name('show');
            Route::post('transactions', [AccountingController::class, 'store'])->name('transactions.store');
            Route::delete('transactions/{transaction}', [AccountingController::class, 'destroy'])->name('transactions.destroy');
        });

        // Agendas
        Route::resource('agendas', AgendaController::class)->only(['index', 'show', 'destroy']);
        Route::resource('preparer-agendas', PreparerAgendaController::class)->only(['index', 'show', 'destroy']);
        Route::resource('client-agendas', \App\Http\Controllers\ClientAgendaController::class)->only(['index', 'show', 'destroy']);

        // Sections of Laws
        Route::resource('sections-of-laws', SectionOfLawController::class);

        // Instructions
        Route::resource('instructions', InstructionController::class);

        // Branches of Laws
        Route::resource('branches-of-laws', BranchOfLawController::class);

        // Laws within Branches
        Route::resource('branches-of-laws.laws', LawController::class);

        // Contracts
        Route::resource('drafting-contracts', DraftingContractController::class);
        Route::resource('drafting-lawsuits', DraftingLawsuitController::class);
        Route::resource('measures', MeasureController::class);

        // Store
        Route::prefix('store')->name('store.')->group(function() {
            Route::resource('categories', StoreCategoryController::class);
            Route::get('products/search', [StoreProductController::class, 'search'])->name('products.search');
            Route::resource('products', StoreProductController::class);
            Route::resource('orders', StoreOrderController::class);
            Route::post('orders/{order}/status', [StoreOrderController::class, 'updateStatus'])->name('orders.update-status');
        });

        // Reviews
        Route::resource('reviews', ReviewController::class);
        Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');

        // Reservations
        Route::resource('reservations', ReservationController::class)->only(['index', 'show']);
        Route::post('reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.update-status');
        
        // API endpoints for reservations
        Route::get('api/lawyers/search', [ReservationController::class, 'searchLawyers'])->name('api.lawyers.search');

        // Notifications routes
        Route::get('notifications/unread', [NotificationController::class, 'getUnreadNotifications'])->name('notifications.unread');
        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

        // Hosting routes
        Route::prefix('hosting')->name('hosting.')->group(function() {
            Route::get('/', [HostingController::class, 'index'])->name('index');
            Route::get('/settings', [HostingController::class, 'settings'])->name('settings');
            Route::post('/settings', [HostingController::class, 'storeSettings'])->name('settings.store');

            // Hosting reservations management
            Route::get('/reservations', [HostingReservationController::class, 'index'])->name('reservations.index');
            Route::get('/reservations/{reservation}', [HostingReservationController::class, 'show'])->name('reservations.show');
            Route::post('/reservations/{reservation}/approve', [HostingReservationController::class, 'approve'])->name('reservations.approve');
            Route::post('/reservations/{reservation}/reject', [HostingReservationController::class, 'reject'])->name('reservations.reject');
        });
    });
});






