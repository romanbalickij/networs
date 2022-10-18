<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewayPayment extends Model
{
    use HasFactory;

    public $fillable = [
      'account_id',
      'customer_id',
      'user_id',
      'verified_payment'
    ];

    public function approved() {

        return $this->update(['verified_payment' => true]);
    }
}
