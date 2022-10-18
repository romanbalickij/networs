<?php


namespace App\Services\Payments;


use App\Models\PaymentMethod;

interface GatewayCustomer
{
    public function charge(PaymentMethod $card, $amount);
    public function addCard($token, $name = null);
    public function transaction(float|int $sum);
}
