<?php

use App\Http\Controllers\Api\UserBookmarkController;
use App\Http\Controllers\Api\UserContentCreatorController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserCreatorSuggestion;
use App\Http\Controllers\Api\UserInvoiceController;

Route::group(['middleware' => ['managed', 'pod']], function () {


    Route::group(['middleware' => ['auth:api', 'isBan']], function () {

        Route::get('blocked',             UserController::class);
        Route::get('{user}/invoices',     UserInvoiceController::class);
        Route::post('{user}/blocked',     [UserController::class, 'blocked']);
        Route::post('{user}/unblock',     [UserController::class, 'unblock']);
        Route::post('{user}/bookmark',    UserBookmarkController::class);
    });

    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('refresh-token',      [UserController::class,'refreshToken']);
    });

    //Access: public, limited interactions
    Route::get('creator-suggestions', UserCreatorSuggestion::class);
    Route::get('{author}/posts',      UserContentCreatorController::class);
    Route::get('{user}',              [UserController::class, 'show']);
    Route::get('nickname/{nickname}', [UserController::class, 'nickname']);
});


