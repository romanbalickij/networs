<?php

namespace Database\Factories;

use App\Models\AdCampaign;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdCampaignFactory extends Factory
{

    protected $model = AdCampaign::class;

    public function definition()
    {
        return [
            'name'        => $this->faker->realText(30),
            'click_count' => $this->faker->numberBetween(0,9999)
        ];
    }
}
