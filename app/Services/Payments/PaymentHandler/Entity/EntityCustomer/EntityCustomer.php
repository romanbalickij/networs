<?php


namespace App\Services\Payments\PaymentHandler\Entity\EntityCustomer;

class EntityCustomer implements CustomerInterface
{

    public function __construct(
        public EntityCustomerInterface $customer
    )
    {
        $this->customer = $customer;
    }

    public function id()
    {
        return $this->customer->ownerId();
    }

    public function partner()
    {
        return $this->customer->getReferralPartner();
    }

    public function payload()
    {
        return $this->customer->getOwner();
    }

    public function actualReferralId() {

        return $this->customer->getReferralId();
    }
}
