<?php


namespace App\Services\Filters;


class SubscriptionFilter extends QueryFilter
{


    public function plan($value) {

        $fun = match ($value) {
            'free'  => fn($query) => $query->where('monthly_cost', '<=', 0),
            'paid'  => fn($query) => $query->where('monthly_cost', '>', 0),
            default => fn($query) => $query
        };

        $this->builder->whereHas('plan', $fun);
    }

}
