<?php


namespace App\Services\History;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait Created
{
    protected static function actionCreated() {

        static::created(function (Model $model){

            $action = $model->action('created');
              //  dd('actionCreated History');
            $created = $model->getCreatedColumn($model);

            $model->saveChange($created, $action);
        });
    }

    protected function getCreatedColumn(Model $model) {

         return new ColumnChange(

            $model->getAttribute($this->onlyHistoryColumn())
        );
    }
}
