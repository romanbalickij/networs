<?php


namespace App\Services\Collection;

use App\Enums\PostType;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class PostCollection extends Collection
{

    public function allowPrivatePostFor($user) {

       return $this
           ->filter(
           fn($post) => !($post->access == PostType::PRIVATE and !(Auth::check() and $post->user->allowedPrivatePostFor($user)))

       )->values();
    }

}
