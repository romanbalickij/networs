<?php

namespace App\Models;


use App\Models\Traits\PostStatistic\HasUser;
use App\Services\Builders\PostShowHistoryBuilder;
use App\Services\History\Historyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostShowHistory extends Model
{
    use HasFactory,

         Historyable,
         HasUser;

    protected $fillable = [
        'user_id',
        'post_id'
    ];

    public function post() {

        return $this->belongsTo(Post::class);
    }

    public function newEloquentBuilder($query)
    {
        return new PostShowHistoryBuilder($query);
    }

    public function onlyHistoryColumn() :?string {

        return NULL;
    }

    public function toOwnerHistory() {

        return $this->post->user_id;
    }

    protected static function actionHistoryDelete() {

        return true;
    }

    protected static function actionHistoryCreate() {
        // not use
        return true;
    }

}
