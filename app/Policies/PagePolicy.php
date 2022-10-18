<?php

namespace App\Policies;

use App\Enums\PageType;
use App\Models\Page;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function delete(User $user, Page $page) {

        return $page->type != PageType::CUSTOM;
    }
}
