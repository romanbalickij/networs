<?php


namespace App\Services\Reports\Invoices;

use App\Models\Invoice;
use App\Services\Reports\ReportInterface;

class PlatformInvoiceReport implements ReportInterface
{

    public function getData()
    {
       return Invoice::query()
           ->platform()
           ->get();
    }

    public function renderReport()
    {
        return $this->getData();
    }

}
