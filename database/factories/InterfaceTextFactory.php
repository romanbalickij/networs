<?php

namespace Database\Factories;

use App\Models\InterfaceText;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

class InterfaceTextFactory extends Factory
{

    protected $model = InterfaceText::class;

    public function definition()
    {
        $filepath = public_path('images');

        if(!File::exists($filepath)){
            File::makeDirectory($filepath);
        }

        $image = $this->faker->image(public_path('images'), 640,480);

        return [
            'length_limit'  => $this->faker->numberBetween(400, 9999),
            'images'        => [
                $image,
                $image,
                $image
            ]
        ];
    }
}
