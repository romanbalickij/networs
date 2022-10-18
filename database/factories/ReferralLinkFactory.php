<?php

namespace Database\Factories;

use App\Models\ReferralLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReferralLinkFactory extends Factory
{

    protected $model = ReferralLink::class;

    public function definition()
    {
        return [
            'name' => $this->faker->text(50),
        ];
    }
}
