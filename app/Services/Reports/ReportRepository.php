<?php


namespace App\Services\Reports;


class ReportRepository
{

    private $report;

    private $saver;

    public function __construct(ReportInterface $report, ReportSaveInterface $saver)
    {
        $this->report = $report;
        $this->saver  = $saver;
    }

    public function save() {

        return collect($this->report->renderReport())->map(function ($report){

            return $this->saver->save($report);
        });
    }
}
