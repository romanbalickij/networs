<?php

namespace App\Models;

use App\Services\Builders\SettingBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id',
      'key',
      'value',
    ];

    public function newEloquentBuilder($query)
    {
        return new SettingBuilder($query);
    }
}
