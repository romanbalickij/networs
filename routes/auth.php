<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialiteLoginController;
use App\Http\Controllers\Auth\VerifyEmailController;

Route::group(['middleware' => ['pod']], function () {

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('api');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('api');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth:api')
                ->name('logout');

Route::get('/verify-email/{id}', [VerifyEmailController::class, 'verify'])
                ->middleware(['api', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('verification/resend', [VerifyEmailController::class, 'resend'])
              ->middleware('api');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('api')
                ->name('password.update');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'showResetForm'])
                ->middleware('api')
                ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('api')
                ->name('password.store');

Route::post('/available-account', [AuthenticatedSessionController::class, 'availableAccount'])
              ->middleware('api');

//Socialite
Route::get('redirect/{provider}', [SocialiteLoginController::class, 'redirectToProvider'])
               ->middleware(['api', 'sessions'])
                ->name('socialite.redirect');

Route::get('{provider}/callback', [SocialiteLoginController::class, 'handleProviderCallback'])
               ->middleware(['api', 'sessions'])
               ->name('socialite.callback');
});
