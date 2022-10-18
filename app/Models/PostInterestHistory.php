<?php

namespace App\Models;


use App\Models\Traits\PostStatistic\HasUser;
use App\Services\Builders\PostInterestHistoryBuilder;
use App\Services\History\Historyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostInterestHistory extends Model
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
        return new PostInterestHistoryBuilder($query);
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

        return true;
    }
}
