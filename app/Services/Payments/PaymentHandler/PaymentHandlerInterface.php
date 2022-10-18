<?php


namespace App\Services\Payments\PaymentHandler;


use App\Models\PaymentMethod;

interface PaymentHandlerInterface
{

    public function __construct(PaymentMethod $paymentMethod);

    public function handler(PaymentOperationInterface $operation);
}
