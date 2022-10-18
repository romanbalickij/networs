<?php

use App\Http\Controllers\Api\PostBookmarkController;
use App\Http\Controllers\Api\PostClickController;
use App\Http\Controllers\Api\PostCommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PostInterestController;
use App\Http\Controllers\Api\PostPinnedController;
use App\Http\Controllers\Api\PostShowController;
use App\Http\Controllers\Api\PostStatisticController;
use App\Http\Controllers\Api\PostTopController;
use App\Http\Controllers\Api\ReactPostController;


Route::group(['middleware' => ['auth:api', 'isBan', 'pod']], function () {

    Route::group(['middleware' => ['managed']], function () {

        Route::get('best-characteristics',  PostTopController::class);
        Route::get('{post}/statistics',     PostStatisticController::class);
        Route::post('{post}/reactions',     ReactPostController::class);
        Route::post('{post}/bookmark',      PostBookmarkController::class);
        Route::post('{post}/pinned',        PostPinnedController::class);
        Route::post('{post}/interest',      PostInterestController::class);
        Route::post('{post}/clicks',        PostClickController::class);
        Route::post('{post}/shows',         PostShowController::class);

        Route::post('/',                   [PostController::class, 'store'])->middleware('userVerified');
        Route::patch('{post}/update',      [PostController::class, 'update'])->middleware('userVerified');
        Route::delete('{post}',            [PostController::class, 'delete']);

        Route::get('{post}/comments',                    PostCommentController::class);
        Route::post('{post}/comments',                   [PostCommentController::class, 'store']);
        Route::post('{post}/comments/{comment}/replies', [PostCommentController::class, 'reply']);
    });

         Route::post('{post}/unlock',                     [PostController::class, 'unlock']);
});


Route::get('',        PostController::class);
Route::get('{post}',  [PostController::class, 'show']);


