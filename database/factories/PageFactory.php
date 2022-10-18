<?php

namespace Database\Factories;

use App\Enums\PageType;
use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{

    protected $model = Page::class;

    public function definition()
    {
        return [
            'robots' => 'robots.txt',
            'type'   => $this->faker->randomElement([PageType::DEFAULT, PageType::CUSTOM])
        ];
    }
}
