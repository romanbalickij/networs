<?php


namespace App\Services\Collection;

use Illuminate\Database\Eloquent\Collection;

class UserCollection extends Collection
{

    public function sumReferralTotalEarned() {

        return $this->reduce(fn($a, $b) => $a + $b->invoices_sum_sum);
    }

}
