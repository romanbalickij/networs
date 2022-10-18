<?php


namespace App\Services\Payments\PaymentHandler;

use App\Services\Payments\PaymentHandler\Entity\EntityCustomer\EntityCustomer;
use Illuminate\Database\Eloquent\Model;

interface PaymentOperationInterface
{


    public function __construct(Model $entity);

    public function pay(PaymentHandlerInterface $paymentHandler);
    public function purpose(string $purpose);
    public function historyType(string $historyType);
    public function getSum():float|int|null;
    public function operationType() :string;
    public function referralOperationType() :string;
    public function addToTransaction($sum);
    public function payload(array $payload);
    public function isTransaction();
    public function paymentStatistics($customer, $type, $entityId, $body);
    public function customer() :EntityCustomer;
}
