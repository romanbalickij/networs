<?php

namespace App\Models;

use App\Models\Traits\General\HasPayment;
use App\Models\Traits\Message\HasBookmark;
use App\Models\Traits\Message\HasChat;
use App\Models\Traits\Message\HasFiles;
use App\Models\Traits\Message\HasNotifications;
use App\Models\Traits\Message\HasReaction;
use App\Models\Traits\Message\HasUser;
use App\Services\Builders\MessageBuilder;
use App\Services\History\Historyable;
use App\Services\Interaction\Interactionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory, SoftDeletes,

        HasChat,
        HasUser,
        HasNotifications,
        HasFiles,
        HasReaction,
        HasBookmark,
        HasPayment,
        Historyable,
        Interactionable;

    protected $fillable = [
        'user_id',
        'chat_id',
        'text',
        'read',
        'is_ppv',
        'ppv_price',
        'meta',
        'send_email',
    ];

    protected $casts = [
        'is_ppv' => 'boolean',
        'read'   => 'boolean'
    ];

    protected $touches = [
        'chat'
    ];

    public function newEloquentBuilder($query)
    {
        return new MessageBuilder($query);
    }


    public static function boot() {
        parent::boot();

        static::deleting(function($message) {

            $message->deleteReactions();
            $message->deleteBookmarks();
            $message->deleteAttachments();
        });
    }

    public function isMe() :bool {

        return $this->user_id === Auth::id();
    }

    public function sendNotificationToEmail() {

        $this->update(['send_email' => true]);
    }

    public function read() {

        return $this->update(['read' => true]);
    }

    public function isReadMessage() {

        return $this->read;

    }

    public function onlyHistoryColumn() :?string {

        return NULL;
    }

    public function toOwnerHistory() {

        return $this->user_id;
    }

    protected static function actionHistoryDelete() {

        return true;
    }

    protected static function actionHistoryCreate() {

        return true;
    }

    public function getOtherUser($currentUserId): ?int {

        $usersId = collect([$this->chat->client_id, $this->chat->service_id]);

        return $usersId->filter(fn($id) => $id != $currentUserId)->first();
    }

}
