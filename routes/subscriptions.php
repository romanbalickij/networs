<?php


use App\Http\Controllers\Api\SubscriptionController;

Route::group(['middleware' => ['auth:api', 'isBan', 'pod']], function () {

    Route::get('/',                SubscriptionController::class);
    Route::delete('{user}/cancel', [SubscriptionController::class, 'cancel']);
});
