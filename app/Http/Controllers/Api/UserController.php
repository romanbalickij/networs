<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Resources\User\BlockingUserCollection;
use App\Http\Resources\User\ContentCreatorResource;
use App\Models\User;
use App\Services\Actions\UserBlockedAction;
use App\Services\Actions\UserUnblockAction;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{

    public function __invoke() {

        $user = user()->load('creatorBlocking.user');

        return $this->respondWithSuccess(

            new BlockingUserCollection($user->blockedUserList()
                ->cursorPaginate($this->perPage())
            )
        );
    }

    public function show($id) {

        $user = User::query()
            ->sumReactionPost()
            ->countMediaPostImageCount()
            ->countMediaPostVideoCount()
            ->countMediaPostCount()
            ->countPost($id, Auth::id())
            ->countSubscribers()
            ->with(['plans.subscription'])
            ->findOrFail($id);

        return $this->respondWithSuccess(

            (new ContentCreatorResource($user))
                ->except('email', 'role', 'location', 'phone', 'address')
        );
    }

    public function nickname($nickname) {

        $user = User::query()
            ->sumReactionPost()
            ->countMediaPostCount()
            ->countMediaPostImageCount()
            ->countMediaPostVideoCount()
            ->countSubscribers()
            ->countPost(User::query()->nickname($nickname)->firstOrFail()->id, Auth::id())
            ->with(['plans.subscription'])
            ->nickname($nickname)
            ->firstOrFail();

        return $this->respondWithSuccess(

            (new ContentCreatorResource($user))
                ->except('email', 'role', 'location', 'phone', 'address')
        );
    }

    public function blocked(User $user, UserBlockedAction $blockedAction) {

        $blockedAction->execute($user);

        return $this->respondWithSuccess('User blocked successfully');
    }

    public function unblock(User $user, UserUnblockAction $unblockAction) {

        $unblockAction->execute($user);

        return $this->respondWithSuccess('User Unblock successfully');
    }

    public function refreshToken() {

        $token = auth()->fromUser(Auth::user());

        return $this->respondWithSuccess(
            [
                'access_token' => $token,
                'token_type'   => 'bearer',
            ]
        );
    }
}
