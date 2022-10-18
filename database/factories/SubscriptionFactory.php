<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{

    protected $model = Subscription::class;

    public function definition()
    {
        return [
            'last_payment_date' => $this->faker->dateTimeBetween('-3 Week','this week'),
            'is_paid'           => $this->faker->randomElement([true, false])
        ];
    }
}
