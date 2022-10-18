<?php

namespace App\Models;

use App\Models\Traits\AccountManager\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountManager extends Model
{
    use HasFactory,

        HasUser;

    protected $fillable = [
      'user_id',
      'manages_user_id',
    ];


}
