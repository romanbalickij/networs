<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberGroup extends Model
{
    use HasFactory;

    protected $fillable = [
      'creator_id',
      'name',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleted(function (SubscriberGroup $subscriberGroup) {

            $subscriberGroup
                ->subscriptions
                ->each(fn($subscription) => $subscription->deleteGroup());
        });
    }

    public function subscriptions() {

        return $this->hasMany(Subscription::class);
    }

    public function latestSubscriptions() {

        return $this->hasMany(Subscription::class)->orderBy('id', 'desc');
    }

    public function subscriptionsTake($count = 3) {

        return $this->latestSubscriptions->take($count);
    }

    public function scopeCountSubscriptions($query) {

        return $query->withCount('subscriptions');
    }
}
