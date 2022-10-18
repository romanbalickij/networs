<?php

namespace Database\Factories;

use App\Enums\MessageType;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'text'      => $this->faker->realText(100),
            'ppv_price' => $this->faker->numberBetween(100, 99999),
            'read'      => $this->faker->randomElement([true, false]),
            'is_ppv'    => $this->faker->randomElement([true, false]),
            'meta'      => $this->faker->randomElement([MessageType::ADMIN_LEFT, MessageType::ADMIN_ENTERED, MessageType::USER_DONATED, MessageType::DEFAULT])
        ];
    }
}
