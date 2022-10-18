<?php

namespace App\Models;

use App\Enums\NotificationType;
use App\Models\Traits\Notification\HasUser;
use App\Services\Builders\NotificationBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Notification extends Model
{
    use HasFactory,
        Prunable,

        HasUser;

    protected $fillable = [
        'user_id',
        'client_id', //sender
        'entity_type',
        'entity_id',
        'read',
    ];

    protected $casts = [
        'read'  => 'boolean',
    ];

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonths(3));
    }

    public function sender() {

        return $this->belongsTo(User::class, 'client_id');
    }

    public function post() {

        return $this->belongsTo(Post::class, 'entity_id');
    }

    public function message() {

        return $this->belongsTo(Message::class, 'entity_id');
    }

    public function subscription() {

        return $this->belongsTo(Subscription::class, 'entity_id');
    }

    public function donation() {

        return $this->belongsTo(Donation::class, 'entity_id');
    }

    public function account() {

        return $this->belongsTo(User::class, 'entity_id');
    }

    public function comment() {

        return $this->belongsTo(Comment::class, 'entity_id');
    }

    public function newEloquentBuilder($query)
    {
        return new NotificationBuilder($query);
    }

    public function read() {

        return $this->update(['read' => true]);
    }
}
