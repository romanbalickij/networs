<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InteractionType;
use App\Enums\NotificationType;
use App\Http\Controllers\BaseController;
use App\Http\Requests\User\SwitchRoleRequest;
use App\Http\Resources\Admin\User\UserCollection;
use App\Models\User;
use App\Notifications\AccountBlockedNotification;
use App\Notifications\AccountMailNotification;
use App\Services\Actions\NewNotificationAction;
use App\Services\Filters\UserFilter;
use Illuminate\Http\Request;

class UserController extends BaseController
{

    public function index(UserFilter $filter) {

        $users = User::filter($filter)
            ->withCount('subscribers')
            ->cursorPaginate($this->perPage());

        return $this->respondWithSuccess(

            (new UserCollection($users))->except('avatar', 'is_online')
        );
    }

    public function verified(User $user, NewNotificationAction $newNotificationAction) {

        $user->verifyAccount();

        $user->pushToInteractions(InteractionType::TYPE_ACCOUNT_VERIFICATION, $user);

        $newNotificationAction->execute($user, NotificationType::ACCOUNT_VERIFIED, $user, user());

        $user->notify(
            (new AccountMailNotification($user))->locale($user->locale)
        );

        return $this->respondWithSuccess('Success');
    }

    public function blocked(User $user, NewNotificationAction $newNotificationAction) {

        $user->blockedAccount();

        $user->pushToInteractions(InteractionType::TYPE_ACCOUNT_BLOCKING, $user);

        $newNotificationAction->execute($user, NotificationType::ACCOUNT_BLOCKED, $user, user());

        $user->notify(
            (new AccountBlockedNotification($user))->locale($user->locale)
        );

        return $this->respondWithSuccess('Success');
    }

    public function unblocked(User $user, NewNotificationAction $newNotificationAction) {

        $user->unblockedAccount();

        $user->pushToInteractions(InteractionType::TYPE_ACCOUNT_UNBLOCKING, $user);

        $newNotificationAction->execute($user, NotificationType::ACCOUNT_UNBLOCKED, $user, user());

        return $this->respondWithSuccess('Success');
    }

    public function switchRole(User $user, SwitchRoleRequest $request) {

        $user->switchRole($request->role);

        return $this->respondWithSuccess('Success');
    }


}
