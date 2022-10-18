<?php


namespace App\Services\Payments;


use App\Models\User;

interface PaymentGateway
{

    public function withUser(User $user);
    public function createCustomer();
    public function connect($code);
}
