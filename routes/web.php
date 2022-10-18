<?php


use App\Stateful\Controllers\EventController;
use App\Stateful\RatchetAdapter;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;

use Illuminate\Support\Facades\Route;


Route::fallback(function () {

    return file_get_contents(public_path('index.html'));
   // return view('welcome');
});



WebSocketsRouter::webSocket('socket', RatchetAdapter::class);
WebSocketsRouter::post('event', EventController::class);

