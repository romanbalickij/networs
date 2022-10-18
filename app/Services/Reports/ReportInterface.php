<?php


namespace App\Services\Reports;


interface ReportInterface
{
    public function getData();
    public function renderReport();
}
