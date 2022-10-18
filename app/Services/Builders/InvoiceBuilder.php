<?php

namespace App\Services\Builders;

use App\Enums\InvoiceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class InvoiceBuilder extends Builder
{

    public function statisticEarned($from, $to) {

        $this->selectRaw('CAST(sum(invoices.sum) AS UNSIGNED) as total_earned')
            ->groupBy('creator_id')
            ->where('type_received', '!=', InvoiceType::MAKE_PAYMENT)
            ->whereBetween('invoices.updated_at', [$from, $to]);

        return $this;
    }

    public function exceptType(...$type) {

       return $this->whereNotIn('type', [...$type]);
    }

    public function currentCreator() {

        return $this->where('creator_id', Auth::id());
    }

    public function filter($filters) {

        return $filters->apply($this);
    }

    public function invitedInvoices() {

        return $this->currentCreator()->whereIn('type', [
            InvoiceType::REFERRAL_PPV,
            InvoiceType::REFERRAL_SUBSCRIPTION_PAYMENT,
            InvoiceType::REFERRAL_DONATION,
        ]);
    }

    public function platform() {

        return $this->where('type_received',  InvoiceType::RECEIVE_PLATFORM);
    }

}
