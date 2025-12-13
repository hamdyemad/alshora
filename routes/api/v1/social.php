<?php

use App\Http\Controllers\Api\v1\CommentController;
use App\Http\Controllers\Api\v1\LikeController;
use App\Http\Controllers\Api\v1\PostController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Posts
    Route::apiResource('posts', PostController::class);

    // Comments
    Route::get('posts/{post_id}/comments', [CommentController::class, 'index']);
    Route::post('comments', [CommentController::class, 'store']);
    Route::put('comments/{id}', [CommentController::class, 'update']);
    Route::delete('comments/{id}', [CommentController::class, 'destroy']);

    // Likes
    Route::post('likes/toggle', [LikeController::class, 'toggle']);
});
