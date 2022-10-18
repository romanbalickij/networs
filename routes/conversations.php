<?php


use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageBookmarkController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ReactMessageController;

Route::group(['middleware' => ['auth:api', 'pod']], function () {

    Route::group(['middleware' => ['managed']], function () {

        Route::get('/',                ConversationController::class);
        Route::get('{chat}/messages',  [ConversationController::class, 'messages']);
        Route::post('{chat}/send',     [ConversationController::class, 'send'])->middleware('allowedChat');
        Route::post('/',               [ConversationController::class, 'store'])->middleware('allowedChat');
        Route::post("mass-send",       [ConversationController::class, 'groupSend'])->middleware('allowedChat');

        Route::patch('message/reads',              [MessageController::class, 'reads']);
        Route::post('message/{message}/bookmark',  MessageBookmarkController::class);
        Route::post('message/{message}/reactions', ReactMessageController::class);
        Route::post('message/{message}/read',      [MessageController::class, 'read']);
        Route::delete('message/{message}/delete',  [MessageController::class, 'delete']);
        Route::get('support',                      [MessageController::class, 'support']);
    });

       Route::post('message/{message}/unlock',     [MessageController::class, 'unlock']);
});
