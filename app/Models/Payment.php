<?php

namespace App\Models;

use App\Models\Traits\Payment\HasInvoice;
use App\Services\Builders\PaymentBuilder;
use App\Services\Collection\PaymentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory,

        HasInvoice;

    protected $fillable = [
       'user_id',
       'sum'
    ];


    public function entity() {

        return $this->morphTo();
    }

    public function newEloquentBuilder($query)
    {
        return new PaymentBuilder($query);
    }

    public function newCollection(array $models = [])
    {
        return new PaymentCollection($models);
    }
}
