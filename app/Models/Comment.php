<?php

namespace App\Models;

use App\Models\Traits\Comment\HasNotifications;
use App\Models\Traits\Comment\HasReplies;
use App\Models\Traits\Comment\HasUser;
use App\Services\Builders\CommentBuilder;
use App\Services\History\Historyable;
use App\Services\Interaction\Interactionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Comment extends Model
{
    use HasFactory,

        HasUser,
        HasReplies,
        HasNotifications,
        Interactionable,
        Historyable;

    protected $fillable = [
      'user_id',
      'post_id',
      'responds_to_id',
      'content',
      'moderated',
    ];

    protected $casts = [
        'moderated'  => 'boolean',
    ];

    public function post() {

        return $this->belongsTo(Post::class);
    }

    public function newEloquentBuilder($query)
    {
        return new CommentBuilder($query);
    }

    public function getContentAttribute($value) {

        return !$this->moderated ? $value : null;
    }

    public function onlyHistoryColumn() :?string {

        return NULL;
    }

    public function toOwnerHistory() {

        return $this->post->user_id;
    }

    public function toggle() {

        return $this->update([ 'moderated' => !$this->moderated ]);
    }

    protected static function actionHistoryDelete() {

        return true;
    }

    protected static function actionHistoryCreate() {

        return true;
    }
}
