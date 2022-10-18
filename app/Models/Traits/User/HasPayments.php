<?php


namespace App\Models\Traits\User;

use App\Models\GatewayPayment;
use App\Models\PaymentMethod;

trait HasPayments
{

    public function paymentMethods() {

        return $this->hasMany(PaymentMethod::class);
    }

    public function defaultPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::class)
            ->whereDefault();
    }

    public function gatewayPayment() {

        return $this->hasOne(GatewayPayment::class);
    }

    public function getGatewayCustomerAttribute($value) {

        return $this->gatewayPayment->customer_id ?? NULL;
    }

    public function isVerifieldPaymentAccount() {

        return (bool) optional($this->gatewayPayment)->verified_payment;
    }

    public function setPaymentAccount(string $accountId) {

        return $this->gatewayPayment()->update(['account_id' => $accountId]);
    }

    public function paymentAccount() {

        return optional($this->gatewayPayment)->account_id;
    }

    public function addToBalance(int|float $sum) :void {

        $this->update(['balance' => $this->balance + $sum]);
    }

    public function withdrawBalance(int|float $sum) {

        return $this->update(['balance' => $this->balance - $sum]);
    }
}
