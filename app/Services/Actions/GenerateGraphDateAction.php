<?php


namespace App\Services\Actions;


use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GenerateGraphDateAction
{

    public function handler($from, $to, $data) {

       return collect(CarbonPeriod::create(Carbon::parse($from), Carbon::parse($to))
           ->toArray())
           ->map(fn($date) => $this->generate($date, $data));
    }

    protected function generate($date, $payload) :?array {

        return [
            'data'  => $day = Carbon::parse($date)->format('Y-m-d'),
            'total' => $payload[$day]->count ?? 0
        ];
    }
}
