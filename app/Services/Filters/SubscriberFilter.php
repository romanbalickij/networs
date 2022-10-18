<?php


namespace App\Services\Filters;


class SubscriberFilter extends QueryFilter
{

    public function group($value) {

        $this->builder->whereHas('subscriptionGroup', fn($query) => $query->where('name', $value));
    }

    public function plan($value) {

        $this->builder->whereHas('plan', fn($query) => $query->where('name', $value));
    }

    public function search($value) {

        $this->builder->whereHas('user', fn($query) => $query->where(

            fn ($builder) => $builder
                ->where('name', 'LIKE', "%$value%")
                ->orWhere('surname', 'LIKE', "%$value%")
            )
        );
    }

}
