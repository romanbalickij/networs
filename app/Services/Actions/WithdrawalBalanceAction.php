<?php


namespace App\Services\Actions;


use App\Services\Payments\PaymentGateway;

class WithdrawalBalanceAction
{

    public function handler($sum) :void {

        app(PaymentGateway::class)
            ->withUser(user())
            ->getCustomer()
            ->transaction($sum);

        user()->withdrawBalance($sum);
    }
}
