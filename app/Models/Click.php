<?php

namespace App\Models;

use App\Models\Traits\Click\HasCampaign;
use App\Services\Builders\ClickBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Click extends Model
{
    use HasFactory,

        HasCampaign;

    protected $fillable = [
        'ad_campaign_id',
        'user_agent',
        'user_ip',
        'user_id'
    ];

    public function getCreatedAtAttribute($value) {

        return Carbon::parse($value)->format('d-m-Y');
    }


    public function newEloquentBuilder($query)
    {
        return new ClickBuilder($query);
    }
}
