<?php


namespace App\Services\Actions;

use App\Models\Page;

class ContentUpdateAction
{

    public function execute($payload, Page $page) {

        return tap($page)->update($payload);
    }
}
