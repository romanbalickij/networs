<?php

use App\Http\Controllers\Api\AccountManagerController;
use App\Http\Controllers\Api\AdCampaignController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\ClickCampaignController;
use App\Http\Controllers\Api\CookieController;
use App\Http\Controllers\Api\CreatorLandingController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\ReferralLinkController;
use App\Jobs\QueuedEmail;


Route::group(['middleware' => ['auth:api', 'pod']], function () {

    Route::group(['middleware' => ['isBan']], function () {

        Route::apiResources([
            'plans'            => PlanController::class,
            'campaigns'        => AdCampaignController::class,
            'referral-links'   => ReferralLinkController::class,
            'account-managers' => AccountManagerController::class,
            'donations'        => DonationController::class,
            'paymentMethods'   => PaymentMethodController::class
        ]);


        Route::group(['middleware' => ['managed']], function () {

                Route::patch('notifications/reads', [NotificationController::class, 'reads']);

                Route::apiResources([
                    'bookmarks'       => BookmarkController::class,
                    'notifications'   => NotificationController::class,
                ]);

        });
    });

});

Route::post('campaigns-click/{campaign}', ClickCampaignController::class);
Route::get('cookies',                     [CookieController::class,'index'])->middleware('localization');
Route::get('creator/{username}',         [CreatorLandingController::class, 'show'])->name('creator.landing')->middleware('localization');

//Route::stripeWebhooks('stripe-webhook');

if(env('APP_ENV') != 'production') {
    Route::get('/debug', [\App\Http\Controllers\DebugController::class, 'debug'])->middleware(['sessions', 'api']);
    Route::get('/testServer', [\App\Http\Controllers\DebugController::class, 'testServer'])->middleware('sessions');
    Route::get('/debug/connect', [\App\Http\Controllers\DebugController::class, 'testConnect'])->middleware('sessions');
    Route::get('/notification/{notification}', \App\Http\Controllers\NotificationDebugController::class);
    Route::get('/docs', [\App\Http\Controllers\DebugController::class, 'docs']);

    Route::get('/empty', function() { return 'hello';});

    Route::get('/queue-jobs', function() {
        for ($i = 0; $i < 3; $i++) {
            dispatch(new QueuedEmail);
        }
    });

}

Route::middleware('signed')->group(function () {

    Route::get('signed/download',  [InvoiceController::class, 'save'])->name('notification.download.invoice');
});




