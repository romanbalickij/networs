<?php

use App\Models\Chat;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;


//Broadcast::channel('conversation.{chat_id}', function ($user, $chat_id) {
//        // text
//    return Chat::find($chat_id)->hasUser($user);
//});


//Broadcast::channel('conversation', function ($user) {
//    // text
//    return Auth::check();
// //   return Chat::find($chat_id)->hasUser($user);
//});
//
//Broadcast::channel('conversation-typing.{chat_id}', function ($user, $chat_id) {
//        // text
//
//    return Chat::find($chat_id)->hasUser($user);
//});
//
//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});
