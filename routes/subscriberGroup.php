<?php


use App\Http\Controllers\Api\SubscriberGroupController;

Route::group(['middleware' => ['auth:api', 'isBan', 'pod']], function () {

    Route::get('/',                                                   [SubscriberGroupController::class, 'index']);
    Route::post('/',                                                  [SubscriberGroupController::class, 'store']);
    Route::patch('{subscriberGroup}',                                 [SubscriberGroupController::class, 'update']);
    Route::patch('{subscriberGroup}/subscription/{subscription}/join',[SubscriberGroupController::class, 'joinSubscription']);
    Route::delete('/subscription/{subscription}/delete',              [SubscriberGroupController::class, 'deleteSubscriptionGroup']);
    Route::delete('{subscriberGroup}/delete',                         [SubscriberGroupController::class, 'delete']);
});
