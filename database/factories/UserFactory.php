<?php

namespace Database\Factories;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'              => $this->faker->firstName,
            'surname'           => $this->faker->lastName,
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token'    => Str::random(10),
            'role'              => UserType::USER,
            'avatar'            => public_path('images/avatar.png'),
            'background'        => public_path('images/background.png'),
            'description'       => $this->faker->realText(300),
            'location'          => $this->faker->randomElement(['Pasadena', 'Kyiv', 'Ivano-Frankivsk']),
            'url'               => $this->faker->domainName,
            'activity_status'   => $this->faker->randomElement([UserType::ACTIVE, UserType::INACTIVE, UserType::BUSY]),
            'locale'            => $this->faker->randomElement(['en', 'ru']),
            'balance'           => $this->faker->numberBetween(100, 99999),
            'phone'             => $this->faker->phoneNumber,
            'verified'          => $this->faker->randomElement([true, false]),
            'blocked'           => $this->faker->randomElement([true, false]),
            'is_online'         => $this->faker->randomElement([true, false]),
            'address'           => $this->faker->address,
            'business_address'  => $this->faker->streetAddress,
            'tax_number'        => $this->faker->iban,
            'count_subscribers' => $this->faker->numberBetween(1000, 99999)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => now()->toDateString(),
            ];
        });
    }
}
