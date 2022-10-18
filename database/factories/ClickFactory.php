<?php

namespace Database\Factories;

use App\Models\Click;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClickFactory extends Factory
{
    protected $model = Click::class;

    public function definition()
    {
        return [
            'user_agent' => $this->faker->userAgent,
            'user_ip'    => $this->faker->localIpv4,
        ];
    }
}
