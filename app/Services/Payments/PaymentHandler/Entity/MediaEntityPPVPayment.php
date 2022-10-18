<?php


namespace App\Services\Payments\PaymentHandler\Entity;

use App\Enums\HistoryType;
use App\Enums\InvoiceType;
use App\Services\Actions\StoreHistoryAction;
use App\Services\Payments\PaymentHandler\Entity\EntityCustomer\EntityCustomer;
use App\Services\Payments\PaymentHandler\Entity\EntityCustomer\MediaCustomer;
use App\Services\Payments\PaymentHandler\PaymentOperationInterface;
use App\Services\Payments\PaymentHandler\PaymentHandlerInterface;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MediaEntityPPVPayment implements PaymentOperationInterface
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

    public function getSum():float|int|null {

        return $this->entity->ppv_price;
    }

    public function operationType() :string {

        return InvoiceType::PPV;
    }

    public function referralOperationType() :string {

        return InvoiceType::REFERRAL_PPV;
    }

    public function isTransaction() {

        $this->storeTransaction = true;

        return $this;
    }

    public function addToTransaction($sum) {

        $this->entity->payments()->create([
            'user_id' => Auth::id(), //unlock media from this user
            'sum'     => $sum       // post_earnings  or message_earnings statistics from creator
        ]);

       $this->paymentStatistics(
           $this->customer()->id(),
           $this->historyType,
           $this->entity->id,
           $sum
       );

       if($this->historyType == HistoryType::POST) {
           $this->entity->addPpvEarned($sum);
       }


        return $this;
    }

    public function historyType(string $historyType)
    {
        $this->historyType = $historyType;

        return $this;
    }

    public function customer():EntityCustomer {

        return new EntityCustomer(new MediaCustomer($this->entity));
    }

    public function paymentStatistics($customer, $type, $entityId, $sum) {

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
