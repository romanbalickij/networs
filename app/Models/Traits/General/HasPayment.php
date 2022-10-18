<?php


namespace App\Models\Traits\General;


use App\Models\Payment;

trait HasPayment
{

    public function payments() {

        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function addPpvEarned(int|float|null$sum) {

        $this->update(['ppv_earned' => $this->ppv_earned + $sum]);

        $this->addPpvUserPaidCount();

        return $this;
    }

    public function addPpvUserPaidCount() {

        $this->update(['ppv_user_paid' => ++$this->ppv_user_paid]);

        return $this;
    }

    public function isPayFor($userId) {

        if(!collect($this->payments)->count()) {
            return false;
        }

        return $this->isMe()
            ? $this->payments->contains(fn($payment) => $payment->user_id == $this->getOtherUser($userId))
            : $this->payments->contains(fn($payment) => $payment->user_id == $userId);
    }



}
