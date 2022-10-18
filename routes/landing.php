<?php

use App\Http\Controllers\Landing\LandingController;
use App\Http\Controllers\Landing\LandingFooterCustomController;
use App\Http\Controllers\Landing\LandingNavigationCustomController;
use App\Http\Controllers\Landing\PageController;


Route::group(['middleware' => [ 'localization', 'pod' ]], function () {

    Route::get('pages/footer',       [LandingFooterCustomController::class, 'index']);
    Route::get('pages/navigation',   [LandingNavigationCustomController::class, 'index']);

    Route::get('landing',            [LandingController::class, 'index']);
    Route::get('pages/{page}',       [PageController::class, 'page']);
    Route::get('pages',              [PageController::class, 'index']);
    Route::get('pages-error/{key}',  [PageController::class, 'pageError']);
});



