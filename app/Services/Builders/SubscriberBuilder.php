<?php

namespace App\Services\Builders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SubscriberBuilder extends Builder
{


    public function paid(bool $paid = true) {

       return $this->where('is_paid', $paid);
    }

    public function withinLastMonth() {

      // return $this->where('last_payment_date', '<=>', Carbon::now()->subMonth());
        return $this->whereDate('last_payment_date', '>', \Carbon\Carbon::now()->subMonth());

    }

    public function whereGroup(int $group) {

        return $this->where('subscriber_group_id', $group);
    }

    public function defaultGroup() {

        return $this->whereNotNull('subscriber_group_id');
    }

    public function creator($creator) {

        return $this->where('creator_id', $creator->id);
    }

    public function filter($filters) {

        return $filters->apply($this);
    }

    public function orderByOnline()
    {
       return $this->orderByDesc(User::select('is_online')
            ->whereColumn('subscriptions.creator_id', 'users.id')
            ->latest()
            ->take(1)
        );
    }
}
