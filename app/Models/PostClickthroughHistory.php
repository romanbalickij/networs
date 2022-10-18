<?php

namespace App\Models;

use App\Models\Traits\PostStatistic\HasUser;
use App\Services\Builders\PostClickthroughHistoryBuilder;
use App\Services\History\Historyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostClickthroughHistory extends Model
{
    use HasFactory, HasUser, Historyable;

    protected $fillable = [
        'user_id',
        'post_id'
    ];

    public function post() {

        return $this->belongsTo(Post::class);
    }

    public function newEloquentBuilder($query)
    {
        return new PostClickthroughHistoryBuilder($query);
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
