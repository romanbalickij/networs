<?php


namespace App\Models\Traits\Payment;


use App\Models\Invoice;

trait HasInvoice
{

    public function invoice() {

        return $this->belongsTo(Invoice::class);
    }
}
