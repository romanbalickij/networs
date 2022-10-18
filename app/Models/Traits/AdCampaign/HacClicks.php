<?php

namespace App\Models\Traits\AdCampaign;

use App\Models\Click;

trait HacClicks
{

    public function clicks() {

        return $this->hasMany(Click::class);
    }

    public function addClick($payload) {

        $this->increment('click_count');

        return $this->clicks()->create($payload);
    }
}
