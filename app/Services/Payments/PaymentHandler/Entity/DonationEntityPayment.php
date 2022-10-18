<?php


namespace App\Services\Payments\PaymentHandler\Entity;


use App\Enums\InvoiceType;
use App\Services\Actions\DonateAction;
use App\Services\Actions\StoreHistoryAction;
use App\Services\Payments\PaymentHandler\Entity\EntityCustomer\DonationCustomer;
use App\Services\Payments\PaymentHandler\Entity\EntityCustomer\EntityCustomer;
use App\Services\Payments\PaymentHandler\PaymentHandlerInterface;
use App\Services\Payments\PaymentHandler\PaymentOperationInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class DonationEntityPayment implements PaymentOperationInterface
{
    public string $purpose;

    public string|null $historyType = null;

    public $storeTransaction = false;

    public $params = [];

    public function __construct(
        public Model $entity
    )
    {
        $this->entity = $entity;
    }

    public function pay(PaymentHandlerInterface $paymentHandler)
    {
        return $paymentHandler->handler($this);
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
        return Arr::get($this->params, 'sum');
    }

    public function operationType(): string
    {
        return InvoiceType::DONATION;
    }

    public function referralOperationType(): string
    {
        return InvoiceType::REFERRAL_DONATION;
    }

    public function addToTransaction($sum)
    {
      $donation = app(DonateAction::class)->handler([
            'user_id'    => user()->id,
            'creator_id' => $this->customer()->id(),
            'sum'        => $sum
        ]);

      $this->paymentStatistics(
          $this->customer()->id(),
          $this->historyType,
          $donation->id,
          $sum
      );
    }

    public function isTransaction()
    {
        $this->storeTransaction = true;

        return $this;
    }

    public function customer(): EntityCustomer
    {
        return new EntityCustomer(new DonationCustomer($this->entity));
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

}
