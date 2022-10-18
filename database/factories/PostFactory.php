<?php

namespace Database\Factories;

use App\Enums\PostType;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{

    protected $model = Post::class;

    public function definition()
    {
//        $visible_after = $this->faker->dateTimeBetween('-2 Week','this week');
//
//        $visible_until = $this->faker->dateTimeBetween($visible_after,  strtotime('+2 month'));

        return [
            'text'          => $this->faker->realText(500),
            'access'        => $this->faker->randomElement([PostType::PRIVATE, PostType::PUBLIC]),
            'interested'    => $this->faker->numberBetween(1,10000),
            'clickthroughs' => $this->faker->numberBetween(1,10000),
            'shows'         => $this->faker->numberBetween(1,10000),
            'reaction_count'=> $this->faker->numberBetween(10,99999),
            'is_ppv'        => $this->faker->randomElement([true, false]),
            'ppv_price'     => $this->faker->numberBetween(100,10000),
//            'visible_after' => $visible_after,
//            'visible_until' => $visible_until,
            'is_pinned'     => $this->faker->randomElement([true, false]),
        ];
    }
}
