<?php


namespace App\Services\Payments\PaymentHandler\Entity\EntityCustomer;


use App\Models\User;

interface EntityCustomerInterface
{
    public function getOwner() :User;
    public function ownerId() :int;
    public function getReferralPartner():User|null;
    public function getReferralId():int|null;
}
