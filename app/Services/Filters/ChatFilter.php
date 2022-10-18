<?php


namespace App\Services\Filters;


use App\Models\SubscriberGroup;
use Illuminate\Support\Facades\Auth;

class ChatFilter extends QueryFilter
{
    //todo not use to long
    public function user($value) {

        $this->builder->whereHas('user', fn($query) => $query->where(

            fn ($builder) => $builder
                ->where('name', 'LIKE', "%$value%")
                ->orWhere('surname', 'LIKE', "%$value%"))
        );

        $this->builder->where('client_id', Auth::id())->orwhereHas('creator', fn($query) => $query->where(

            fn ($builder) => $builder
                ->where('name', 'LIKE', "%$value%")
                ->orWhere('surname', 'LIKE', "%$value%"))
        );
    }
    //todo not use to long
    public function group($value) {

        $value = SubscriberGroup::where('creator_id', Auth::id())->where('name', $value)->first();

        $this->builder->whereHas('user.subscriptions', fn ($q) => $q->where('creator_id' ,Auth::id())->where('subscriber_group_id', $value->id));

        $this->builder->orWhereHas('creator.subscriptions', fn ($q) => $q->where('creator_id', Auth::id())->where('subscriber_group_id', $value->id));
    }

    public function recipient($value) {

        $this->builder->whereHas('creator', fn($query) => $query->where(

            fn ($builder) => $builder
                ->where('name', 'LIKE', "%$value%")
                ->orWhere('surname', 'LIKE', "%$value%"))
        );
    }

}
