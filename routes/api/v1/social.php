<?php

use App\Http\Controllers\Api\v1\CommentController;
use App\Http\Controllers\Api\v1\FollowController;
use App\Http\Controllers\Api\v1\LikeController;
use App\Http\Controllers\Api\v1\DislikeController;
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

    // Likes (supports: post, comment, lawyer)
    Route::post('likes/toggle', [LikeController::class, 'toggle']);

    // Dislikes (supports: post, comment, lawyer)
    Route::post('dislikes/toggle', [DislikeController::class, 'toggle']);

    // Follow/Unfollow lawyers
    Route::post('follow/toggle', [FollowController::class, 'toggle']);
    Route::get('follow/my-following', [FollowController::class, 'myFollowing']);
});
