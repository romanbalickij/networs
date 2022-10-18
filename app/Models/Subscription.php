<?php

namespace App\Models;

use App\Models\Traits\Subscription\HasNotifications;
use App\Models\Traits\Subscription\HasPlan;
use App\Models\Traits\Subscription\HasSubscriptionGroup;
use App\Models\Traits\Subscription\HasUser;
use App\Services\Builders\SubscriberBuilder;
use App\Services\History\Historyable;
use App\Services\Interaction\Interactionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Subscription extends Model
{
    use HasFactory,
        SoftDeletes,

        HasUser,
        HasPlan,
        HasSubscriptionGroup,
        HasNotifications,
        Historyable,
        Interactionable;

    const UI_ATTEMPTS = 3;

    protected $fillable = [
        'user_id',
        'plan_id',
        'creator_id',
        'subscriber_group_id',
        'last_payment_date',
        'is_paid',
        'attempt_pay'//only to backend use
    ];

    public function newEloquentBuilder($query)
    {
        return new SubscriberBuilder($query);
    }

    public function nextPayment() {

        return Carbon::parse($this->last_payment_date)
            ->addMonths(1)
            ->format('d-m-Y');
    }

    public function paid()  {

        return tap($this)->update([
            'last_payment_date' => now(),
            'is_paid'           => true,
        ]);
    }

    public function unsubscribe() :void {

        $this->delete();
    }

    protected static function actionHistoryDelete() {

        return true;
    }

    protected static function actionHistoryCreate() {
        // not use
        return false;
    }

}
