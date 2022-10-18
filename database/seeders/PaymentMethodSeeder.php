<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function ($user) {

           $user->paymentMethods()->create([
                'card_type'   => 'Visa',
                'last_four'   => '4242',
                'provider_id' => 'card_1KW2YpKgGhTBjWMtmbkmgujm' //test card stripe
           ]);
        });
    }
}
