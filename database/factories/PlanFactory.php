<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{

    protected $model = Plan::class;

    public function definition()
    {
        $plans = ['Basic', 'Standard', 'Enterprise'];
        return [
            'name'         => $this->faker->randomElement($plans),
            'monthly_cost' => $this->faker->numberBetween(100,999),
            'discount'     => $this->faker->numberBetween(1,50),
            'description'  => $this->faker->text(50)
        ];
    }
}
