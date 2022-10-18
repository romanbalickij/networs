<?php


namespace App\Services\Actions\Invoices;


use App\Models\Invoice;
use App\Services\DataTransferObjects\InvoiceDto;

class GenerateInvoiceAction
{

    public function __invoke(InvoiceDto $invoiceDto) {

        return Invoice::create($invoiceDto->toArray());
    }
}
