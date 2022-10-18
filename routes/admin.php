<?php

use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContentManagementController;
use App\Http\Controllers\Admin\ContentManagementInterfaceController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\ConversationJoinController;
use App\Http\Controllers\Admin\ConversationMessageController;
use App\Http\Controllers\Admin\ConversationOpenIssueController;
use App\Http\Controllers\Admin\ConversationReplyController;
use App\Http\Controllers\Admin\PlatformInvoiceController;
use App\Http\Controllers\Admin\PromotionMessageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserInvoiceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin', 'pod']], function () {

    Route::prefix('users')->group(function (){
         Route::get('/',                        [UserController::class, 'index']);
         Route::patch('{user}/verified',        [UserController::class, 'verified']);
         Route::patch('{user}/blocked',         [UserController::class, 'blocked']);
         Route::patch('{user}/unblocked',       [UserController::class, 'unblocked']);
         Route::patch('{user}/switch-role',     [UserController::class, 'switchRole']);
         Route::get('{user}/export-invoices',   [UserInvoiceController::class, 'download']);
    });

    Route::prefix('invoices')->group(function (){
        Route::get('/',                  [PlatformInvoiceController::class, 'index']);
        Route::get('{invoice}/download', [PlatformInvoiceController::class, 'download']);
        Route::get('exports',            [PlatformInvoiceController::class, 'downloadAll']);
    });

    Route::prefix('pages')->middleware('localization')->group(function (){
       Route::get('/',         [ContentManagementController::class, 'index']);
       Route::post('/',        [ContentManagementController::class, 'store']);
       Route::patch('{page}',  [ContentManagementController::class, 'update']);
       Route::delete('{page}', [ContentManagementController::class, 'delete']);

       Route::get('{page}/interface',             ContentManagementInterfaceController::class);
       Route::patch('interface/{interfaceText}', [ContentManagementInterfaceController::class, 'update']);

    });

    Route::prefix('conversations')->group(function (){
        Route::get('open-issue',       [ConversationOpenIssueController::class, 'open']);
        Route::get('current',          [ConversationController::class, 'current']);
        Route::patch('{chat}/resign',  [ConversationController::class, 'resign']);
        Route::patch('{chat}/join',    [ConversationJoinController::class, 'join']);
        Route::get('{chat}/messages',  ConversationMessageController::class);
        Route::post('{chat}/reply',    [ConversationReplyController::class, 'reply']);
        Route::post("promotion",       [PromotionMessageController::class, 'send']);
    });

    Route::prefix('comments')->group(function (){
        Route::patch('{comment}/moderated',  [CommentController::class, 'moderate']);
    });
});
