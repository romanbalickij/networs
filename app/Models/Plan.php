<?php

namespace App\Models;

use App\Models\Traits\Plan\HasSubscriptions;
use App\Models\Traits\Plan\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory,

        HasSubscriptions,
        HasUser;

    protected $fillable = [
      'user_id',
      'monthly_cost',
      'discount',
      'name',
      'description',
    ];


}
