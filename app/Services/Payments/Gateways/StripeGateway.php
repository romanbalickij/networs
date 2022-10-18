<?php


namespace App\Services\Payments\Gateways;


use App\Models\User;
use App\Services\Payments\PaymentGateway;
use Stripe\OAuth;
use Stripe\Customer as StripeCustomer;
use App\Models\GatewayPayment as UserPayment;

class StripeGateway implements PaymentGateway
{

    protected $user;

    protected $token;

    public function withUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function user()
    {
        return $this->user;
    }

    public function createCustomer()
    {
        if($this->user->gatewayCustomer) {
            return $this->getCustomer();
        }

        $customer = new StripeGatewayTransaction(
            $this, $this->createStripeCustomer()
        );

        UserPayment::updateOrCreate(['user_id' => $this->user->id], [
            'customer_id' => $customer->id()
        ]);

        return $customer;
    }

    public function getCustomer()
    {
        return new StripeGatewayTransaction(
            $this, StripeCustomer::retrieve($this->user->gatewayCustomer)
        );
    }

    public function createStripeCustomer()
    {
        return StripeCustomer::create([
            'email'  => $this->user->email,
        ]);
    }

    public function connect($code) {

        return OAuth::token([
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);

    }

}
