<?php


namespace App\Services\Payments\PaymentHandler\Entity\EntityCustomer;


interface CustomerInterface
{

    public function id();
    public function partner();
    public function payload();
    public function actualReferralId();
}
