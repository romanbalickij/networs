<?php

namespace Database\Factories;

use App\Enums\PageType;
use App\Models\Reaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReactionFactory extends Factory
{

    protected $model = Reaction::class;

    public function definition()
    {
        return [
            'reaction' => $this->faker->randomElement(['❤', '🔥']),
        ];
    }
}
