<?php

namespace App\Models;

use App\Services\Builders\HistoryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function historyable()
    {
        return $this->morphTo();
    }

    public function newEloquentBuilder($query)
    {
        return new HistoryBuilder($query);
    }
}
