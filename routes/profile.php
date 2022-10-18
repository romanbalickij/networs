<?php

use App\Http\Controllers\Api\CreatorLandingController;
use App\Http\Controllers\Api\MyInteractionController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProfileManagedController;
use App\Http\Controllers\Api\StatisticController;
use App\Http\Controllers\Api\StripeConnectController;
use App\Http\Controllers\Api\WithdrawalPaymentBalanceController;

Route::group(['middleware' => ['isBan', 'auth:api', 'pod']], function () {

    Route::get('/',                 [ProfileController::class, 'profile']);
    Route::get('info',              [ProfileController::class, 'info']);
    Route::get('settings',          [ProfileController::class, 'showSettings']);
    Route::patch('settings',        [ProfileController::class, 'updateSettings']);
    Route::post('update',           [ProfileController::class, 'update']);
    Route::delete('delete',         [ProfileController::class, 'delete']);
    Route::delete('avatar',         [ProfileController::class, 'removeAvatar']);
    Route::delete('background',     [ProfileController::class, 'removeBackground']);

    Route::get('managed-accounts',  [ProfileManagedController::class, 'index']);
    Route::patch('managed-accounts',[ProfileManagedController::class, 'setManaged']);

    Route::get('statistics',         StatisticController::class);
    Route::get('interactions',       MyInteractionController::class);

    Route::get('my-landing',                     [CreatorLandingController::class, 'index']);
    Route::post('my-landing',                    [CreatorLandingController::class, 'store']);
    Route::delete('my-landing/{landingCreator}', [CreatorLandingController::class, 'delete']);

    Route::patch('withdrawal-balance',       [WithdrawalPaymentBalanceController::class, 'withdrawal'])->middleware('verifyEmail');
    Route::post('withdrawal-confirmed-send', [WithdrawalPaymentBalanceController::class, 'confirmed'])->middleware('verifyEmail');
});

Route::prefix('connect')->group(function () {
    Route::get('stripe-account', [StripeConnectController::class, 'store']);
});
