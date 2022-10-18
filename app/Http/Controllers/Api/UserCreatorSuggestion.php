<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Controllers\BaseController;
use App\Http\Resources\User\CreatorSuggestionCollection;
use App\Models\User;

class UserCreatorSuggestion extends BaseController
{
    public function __invoke()
    {

        $users = User::query()
            ->where('role', '!=', UserType::ADMIN)
            ->with('bookmarked')
            ->orderByPopularTrendingPostShow()
            ->has('posts')
            ->take(30)
            ->get();

        return $this->respondWithSuccess(

            new CreatorSuggestionCollection($users)
        );
    }
}
