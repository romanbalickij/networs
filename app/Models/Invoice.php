<?php

namespace App\Models;

use App\Models\Traits\Invoice\HasUser;
use App\Services\Builders\InvoiceBuilder;
use App\Services\Interaction\Interactionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Invoice extends Model
{
    use HasFactory,

        Interactionable,
        HasUser;

    protected $fillable = [
        'user_id',
        'creator_id',
        'sum',
        'commission_sum',
        'type',
        'direction',
        'purpose_string',
        'type_received',
        'transaction_crypto_id',
        'crypto_type',
        'status'
    ];

    public function newEloquentBuilder($query)
    {
        return new InvoiceBuilder($query);
    }

    public function getCreatedAtAttribute($value) {

        return Carbon::parse($value)->format('d-m-Y');
    }
}
