<?php


namespace App\Services\Payments\PaymentHandler\Entity;


use App\Enums\InvoiceType;
use App\Enums\TrackFnType;
use App\Models\Plan;
use App\Services\Actions\StoreHistoryAction;
use App\Services\Actions\SubscribeAction;
use App\Services\Actions\TrackFnsAction;
use App\Services\Payments\PaymentHandler\Entity\EntityCustomer\EntityCustomer;
use App\Services\Payments\PaymentHandler\Entity\EntityCustomer\SubscribeCustomer;
use App\Services\Payments\PaymentHandler\PaymentHandlerInterface;
use App\Services\Payments\PaymentHandler\PaymentOperationInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class SubscribeEntityPayment implements PaymentOperationInterface
{

    public string|null $historyType = null;

    public $storeTransaction = false;

    public $params = [];

    public $returnModel;

    public function __construct(
        public Model $entity
    )
    {
        $this->entity = $entity;
    }

    public function pay(PaymentHandlerInterface $paymentHandler)
    {
        $paymentHandler->handler($this);

        return $this;
    }

    public function purpose(string $purpose)
    {
        $this->purpose = $purpose;

        return $this;
    }

    public function historyType(string $historyType)
    {
        $this->historyType = $historyType;

        return $this;
    }

    public function getSum(): float|int|null
    {
        return Plan::find(Arr::get($this->params, 'plan_id'))->monthly_cost;
    }

    public function operationType(): string
    {
        return InvoiceType::SUBSCRIPTION_PAYMENT;
    }

    public function referralOperationType(): string
    {
        return InvoiceType::REFERRAL_SUBSCRIPTION_PAYMENT;
    }

    public function addToTransaction($sum)
    {

      $subscribe = app(SubscribeAction::class)
            ->handler(
                $this->customer()->payload(),
                Plan::find(Arr::get($this->params, 'plan_id'))
            );

      $this->returnModel = $subscribe;

        $this->paymentStatistics(
            $this->customer()->id(),
            $this->historyType,
            $subscribe->id,
            $sum
        );
    }

    public function isTransaction()
    {
        $this->storeTransaction = true;

        return $this;
    }

    public function paymentStatistics($customer, $type, $entityId, $sum)
    {
        app(StoreHistoryAction::class)->action(
            $customer,
            $type,
            $entityId,
            $sum
        );
    }

    public function payload(array $payload)
    {
        $this->params = $payload;

        return $this;
    }

    public function customer(): EntityCustomer
    {
        return new EntityCustomer(new SubscribeCustomer($this->entity));
    }

    public function thenReturn() {

        return $this->returnModel;
    }
}
