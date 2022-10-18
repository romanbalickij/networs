<?php


namespace App\Services\History;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Deleted
{

    protected static function actionDeleted() {

        static::deleted(function (Model $model){

          $model->history()
              ->where('user_id', Auth::id())
              ->delete();
        });
    }

}
