<?php

use App\Http\Controllers\Api\v1\SupportMessageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('support', [SupportMessageController::class, 'store']);
    Route::get('support/my-messages', [SupportMessageController::class, 'myMessages']);
});
