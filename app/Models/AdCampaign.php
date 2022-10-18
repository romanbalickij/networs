<?php

namespace App\Models;

use App\Models\Traits\AdCampaign\HacClicks;
use App\Models\Traits\AdCampaign\HasUser;
use App\Services\Builders\CampaignBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCampaign extends Model
{
    use HasFactory,

        HasUser,
        HacClicks;

    protected $fillable = [
        'user_id',
        'click_count',
        'name',
    ];

    public function newEloquentBuilder($query)
    {
        return new CampaignBuilder($query);
    }
}
