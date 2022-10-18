<?php


namespace App\Services\Payments\PaymentHandler\Entity\EntityCustomer;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SubscribeCustomer implements EntityCustomerInterface
{

    public function __construct(
        public Model $entity
    )
    {
        $this->model = $entity;
    }

    public function getOwner(): User
    {
        return $this->model;
    }

    public function ownerId(): int
    {
        return $this->getOwner()->id;
    }

    public function getReferralPartner(): User|null
    {
        return $this->getOwner()->referralPartner();
    }

    public function getReferralId(): int|null
    {
        return $this->getOwner()->referral_link_id;
    }
}
