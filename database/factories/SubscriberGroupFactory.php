<?php

namespace Database\Factories;

use App\Models\SubscriberGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberGroupFactory extends Factory
{

    protected $model = SubscriberGroup::class;

    public function definition()
    {
        $group = ['Friends', 'Family', 'Work'];

        return [
       //     'name' => $this->faker->randomElement($group )
        ];
    }
}
