<?php

namespace App\Services\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class HistoryBuilder extends Builder
{

    public function type($type) {

       return $this->where('historyable_type', $type);
    }

    public function typeId($id) {

        return $this->where('historyable_id', $id);
    }

    public function sumPPV() {

        return $this->select(DB::raw("SUM(body) as ppv"));
    }

    public function generateGraphs($from, $to) {

        return $this->select([
            DB::raw('DATE(created_at) AS date'),
            DB::raw('COUNT(id) AS count'),

        ])->groupDate($from, $to);
    }

    public function earningGraphs($from, $to) {

        return $this->select([
            DB::raw('DATE(created_at) AS date'),
            DB::raw('SUM(body) as count'),

        ])->groupDate($from, $to);

    }

    public function to(?int $id) {

        return $this->where('user_id', $id);
    }

    public function groupDate($from, $to) {

        return $this
            ->whereBetween('created_at', [$from->toDateString(), $to->toDateString()])
            ->groupBy('date')
            ->orderBy('date', 'ASC');
    }
}
