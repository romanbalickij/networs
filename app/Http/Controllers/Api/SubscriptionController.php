<?php

namespace App\Http\Controllers\Api;

use App\Enums\InteractionType;
use App\Enums\TrackFnType;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Subscription\SubscriptionCollection;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Actions\CancelMySubscribedAction;
use App\Services\Actions\TrackFnsAction;
use App\Services\Filters\SubscriptionFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;

class SubscriptionController extends BaseController
{

    public function __invoke(SubscriptionFilter $subscriptionFilter) {

        return $this->respondWithSuccess(

            new SubscriptionCollection(
                user()
                ->subscriptions()
                ->filter($subscriptionFilter)
                ->with([
                    'owner.bookmarked',
                    'plan',
                    'subscriptionGroup',
                    'owner.post.allComments:id,post_id',
                    'owner.post.reactions',
                    'owner.post.others.entity.payments',
                    'owner.post.media.entity.payments',
                ])
                ->cursorPaginate($this->perPage())
            )
      );
    }

    public function cancel(CancelMySubscribedAction $cancelMySubscribedAction, User $user) {

        try {
            $cancelMySubscribedAction->execute($user);

            app(TrackFnsAction::class)->handler( [TrackFnType::STOP], \user()->external_tracker_id);

            return $this->respondOk('The Subscription cancel successfully');

        }catch (\Exception $exception) {

            return $this->respondError($exception->getMessage());
        }
    }
}
