<?php

namespace Database\Factories;

use App\Enums\ChatType;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{

    protected $model = Chat::class;

    public function definition()
    {
        return [
            'mode' => ChatType::CONTENT_CREATOR
        ];
    }
}
