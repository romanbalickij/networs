<?php

namespace App\Models;

use App\Models\Traits\General\HasPayment;
use App\Models\Traits\Post\HasBookmark;
use App\Models\Traits\Post\HasClickthroughHistories;
use App\Models\Traits\Post\HasComments;
use App\Models\Traits\Post\HasFiles;
use App\Models\Traits\Post\HasInterestHistories;
use App\Models\Traits\Post\HasReaction;
use App\Models\Traits\Post\HasShowHistories;
use App\Models\Traits\Post\HasUser;
use App\Services\Builders\PostBuilder;
use App\Services\Collection\PostCollection;
use App\Services\History\Historyable;
use App\Services\Interaction\Interactionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory,

        HasUser,
        HasComments,
        HasReaction,
        HasBookmark,
        HasShowHistories,
        HasInterestHistories,
        HasClickthroughHistories,
        HasPayment,
        Interactionable,
        Historyable,
        HasFiles;


    protected $fillable = [
        'user_id',
        'text',
        'access',
        'interested',
        'clickthroughs',
        'reaction_count',
        'shows',
        'is_ppv',
        'is_pinned',
        'ppv_price',
        'visible_after',
        'visible_until',
        'quality_for_creator',
        'quality',
        'media_count',
        'ppv_earned',
        'ppv_user_paid',
        'video_count',
        'image_count'
    ];

    protected $casts = [
        'is_ppv'    => 'boolean',
        'is_pinned' => 'boolean',
    ];

    public function newEloquentBuilder($query)
    {
        return new PostBuilder($query);
    }

    public function newCollection(array $models = [])
    {
        return new PostCollection($models);
    }

    public function pinned() {

        $this->update(['is_pinned' => true]);
    }

    public function unpinned() {

        $this->update(['is_pinned' => false]);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($post) {

            $post->deleteReactions();
            $post->deleteBookmarks();
            $post->deleteAttachments();

        });
    }

    protected static function actionHistoryDelete() {

        return true;
    }

    protected static function actionHistoryCreate() {
        // not use
        return false;
    }

    //TODO  costile
    public function getVisibleUntilAttribute($value) {

        return $value == '0000-00-00 00:00:00' ? $this->transformData($value) : $value;
    }
    //TODO  costile
    protected function transformData($value)
    {
        return is_string($value) && $value === '0000-00-00 00:00:00' ? null : $value;
    }

    public function isMy() :bool{

        return $this->user_id == Auth::id();
    }
}
