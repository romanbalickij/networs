<?php

namespace Database\Factories;

use App\Enums\InvoiceType;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{

    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'sum'  => $this->faker->numberBetween(100, 9999),
            'type' => $this->faker->randomElement([
                InvoiceType::SUBSCRIPTION_PAYMENT,
                InvoiceType::DONATION,
                InvoiceType::PPV,
                InvoiceType::REFERRAL_SUBSCRIPTION_PAYMENT,
                InvoiceType::REFERRAL_DONATION,
                InvoiceType::REFERRAL_PPV,
                InvoiceType::COMMISSION,
                InvoiceType::WITHDRAWAL
            ]),
            'type_received' => $this->faker->randomElement([
                InvoiceType::RECEIVED_PAYMENT,
                InvoiceType::MAKE_PAYMENT,
                InvoiceType::RECEIVE_PLATFORM,
                InvoiceType::RECEIVED_REFERRAL,
            ]),
            'direction'      => $this->faker->randomElement([InvoiceType::DIRECTION_DEBIT, InvoiceType::DIRECTION_CREDIT]),
            'purpose_string' => $this->faker->text(100),
            'commission_sum' => $this->faker->numberBetween(5,20),

        ];
    }
}
