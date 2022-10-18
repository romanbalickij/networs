<?php


namespace App\Services\Collection;

use Illuminate\Database\Eloquent\Collection;

class PaymentCollection extends Collection
{

    public function earned() {

        return $this->reduce(fn($a, $b) => $a + $b->sum);
    }

    public function isPay($user) {

        return $this
            ->map(fn ($payment) => $payment->user_id)
            ->contains($user);
    }
}
