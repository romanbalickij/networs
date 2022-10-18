<?php

namespace Database\Factories;

use App\Models\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    protected $model = History::class;

    public function definition()
    {
        return [
            'action' => 'created',
            'body'   => $this->faker->randomElement([rand(1,1000), NULL])
        ];
    }
}
