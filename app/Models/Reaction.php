<?php

namespace App\Models;

use App\Models\Traits\Reaction\HasNotifications;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory,

        HasNotifications;

    protected $fillable = [
        'entity_type',
        'entity_id',
        'reaction',
        'type',
        'user_id'
    ];
}
