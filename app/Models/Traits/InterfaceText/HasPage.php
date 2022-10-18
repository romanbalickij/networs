<?php

namespace App\Models\Traits\InterfaceText;

use App\Models\Page;

trait HasPage
{

    public function page() {

        return $this->belongsTo(Page::class);
    }
}
