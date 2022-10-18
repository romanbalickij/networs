<?php


namespace App\Services\Reports\Invoices;

use App\Models\User;
use App\Services\Reports\ReportInterface;

class InvoiceReport implements ReportInterface
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getData()
    {
       return $this->user->creatorInvoices;
    }

    public function renderReport()
    {
        return $this->getData();
    }

}
