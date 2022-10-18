<?php


use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\FileUploadController;

Route::group(['middleware' => ['auth:api', 'isBan', 'pod']], function () {

    Route::group(['middleware' => ['managed']], function () {

        Route::get('/{file}',           AttachmentController::class);
        Route::get('/{file}/download',  [AttachmentController::class, 'download']);
        Route::post('/{file}/bookmark', [AttachmentController::class, 'bookmark']);

        Route::post('uploads', [FileUploadController::class, 'upload']);
    });
});

