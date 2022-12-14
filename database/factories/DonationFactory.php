<?php

namespace Database\Factories;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{

    protected $model = Donation::class;

    public function definition()
    {
        return [
            'sum' => $this->faker->numberBetween(100, 900)
        ];
    }
}
