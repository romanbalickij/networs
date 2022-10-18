<?php

namespace App\Models;

use App\Models\Traits\BlockedUser\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    use HasFactory,

        HasUser;

    protected $fillable = [
      'user_id',
      'bloquee_id',
    ];
}
