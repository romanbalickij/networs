<?php

namespace App\Models;

use App\Services\Builders\PaymentMethodBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    public $fillable = [
      'user_id',
      'card_type',
      'last_four',
      'provider_id',
      'default',
       'name'
    ];

    public function user() {

        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($paymentMethod) {
            if($paymentMethod->default) {
                $paymentMethod->user->paymentMethods()->update([
                    'default' => false
                ]);
            }
        });
    }

    public function setDefaultAttribute($value) {

        $this->attributes['default'] = ($value === 'true' || $value ? true : false);
    }

    public function newEloquentBuilder($query)
    {
        return new PaymentMethodBuilder($query);
    }
}
