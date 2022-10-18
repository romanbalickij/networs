<?php

namespace Database\Seeders;

use App\Enums\InvoiceType;
use App\Models\Invoice;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{

    public function run()
    {
        DB::table('payments') -> truncate();

        Post::take(250)->get()->each(function ($post) {

           $post->payments()->create([
               'user_id' => User::whereNotIn('id', [$post->user_id])->inRandomOrder()->first()->id,
               'sum'     => rand(100,500)
           ]);
        });

    }
}
