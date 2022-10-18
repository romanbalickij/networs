<?php


namespace App\Services\Payments\Gateways;


use App\Models\PaymentMethod;
use App\Services\Payments\GatewayCustomer;
use App\Services\Payments\PaymentGateway;
use Stripe\Charge as StripeCharge;
use Stripe\Customer as StripeCustomer;
use App\Exceptions\PaymentFailedException;
use Stripe\Transfer;

class StripeGatewayTransaction implements GatewayCustomer
{

    const CURRENCY_USD = 'usd';

    public function __construct(
        protected PaymentGateway $paymentGateway,
        protected StripeCustomer $customer

    ){
        $this->gateway  = $paymentGateway;
        $this->customer = $customer;
    }

    public function charge(PaymentMethod $card, $amount)
    {
        try {

            StripeCharge::create([
                'currency' => 'usd',
                'amount'   => self::toStripeFormat($amount),
                'customer' => $this->customer->id,
                'source'   => $card->provider_id
            ]);

        }catch (\Exception $e) {
            throw new PaymentFailedException($e->getMessage());
        }
    }

    public function addCard($token, $name = null)
    {

        $card = $this->customer->createSource(
            $this->id(),
            ['source' => $token]
        );

        return $this->paymentGateway->user()->paymentMethods()->create([
            'provider_id' => $card->id,
            'card_type'   => $card->brand,
            'last_four'   => $card->last4,
            'default'     => true,
            'name'        => $name
         ]);
    }

    public function deleteCard(PaymentMethod $card) {

        try {
            $this->customer->deleteSource($this->id(), $card->provider_id);

            $card->delete();

        }catch (\Exception $e) {
            throw new PaymentFailedException($e->getMessage());
        }
    }

    public function id() {

        return $this->customer->id;
    }

    public function transaction(float|int $sum)
    {
        try {

            return Transfer::create([
                'amount'       => self::toStripeFormat($sum),
                "currency"     => self::CURRENCY_USD,
                'destination'  => $this->paymentGateway->user()->paymentAccount()
            ]);
        }catch (\Exception $exception) {

            throw new PaymentFailedException($exception->getMessage());
        }

    }

    private function toStripeFormat($amount)
    {
        return $amount * 100;
    }
}
