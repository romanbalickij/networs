<?php

use App\Http\Controllers\Api\SubscriberController;

Route::group(['middleware' => ['auth:api', 'isBan', 'pod']], function () {

    Route::get('/',             SubscriberController::class);
    Route::post('{user}',      [SubscriberController::class, 'subscribe']);
});
