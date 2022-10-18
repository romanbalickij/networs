<?php

namespace App\Models\Traits\User;

use App\Enums\InvoiceType;
use App\Models\Invoice;

trait HasInvoices
{
    //  counterparty
    public function invoices() {

        return $this->hasMany(Invoice::class);
    }

    // my invoices
    public function creatorInvoices() {

        return $this
            ->hasMany(Invoice::class, 'creator_id')
            ->where('type_received', '!=', InvoiceType::RECEIVE_PLATFORM);
    }

    public function totalEarned($from, $to) {

        return $this->creatorInvoices()
            ->statisticEarned($from, $to)
            ->exceptType(InvoiceType::COMMISSION, InvoiceType::WITHDRAWAL)
            ->first();
    }

}
