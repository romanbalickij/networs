<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserPostSeeder extends Seeder
{

    public function run()
    {
        User::factory(1)
            ->has(
                Post::factory()
                    ->count(5)

            )->create([
                'email'     => 'user@user.com',
                'password'  => Hash::Make('user@user.com'),
                'verified'  => true,
                'blocked'   => false,
            ]);
    }
}
