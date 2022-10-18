<?php

namespace App\Http\Controllers\Api;

use App\Enums\HistoryType;
use App\Enums\NotificationType;
use App\Enums\TrackFnType;
use App\Exceptions\PaymentFailedException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Subscriber\SubscriberRequest;
use App\Http\Resources\Chat\ConversationResource;
use App\Http\Resources\Subscriber\SubscriberCollection;
use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\User;
use App\Notifications\SubscribeMailNotification;
use App\Services\Actions\ErrorHandlerAction;
use App\Services\Actions\NewNotificationAction;
use App\Services\Actions\TrackFnsAction;
use App\Services\Chat\ChatService;
use App\Services\Chat\ConversationUser;
use App\Services\Filters\SubscriberFilter;
use App\Services\Payments\PaymentHandler\Entity\SubscribeEntityPayment;
use App\Services\Payments\PaymentHandler\PaymentHandler;

class SubscriberController extends BaseController
{

    public $chat;

    public function __construct()
    {
        $this->chat = new ChatService(new ConversationUser());
    }

    public function __invoke(SubscriberFilter $subscriberFilter) {

        $userCurrent = user()->load(['subscribers']);

        return $this->respondWithSuccess(

            new SubscriberCollection(
                $userCurrent
                ->subscribers()
                ->filter($subscriberFilter)
                ->with(['plan', 'user.userSettings', 'subscriptionGroup'])
                ->cursorPaginate($this->perPage())
            )
        );
    }

    public function subscribe(SubscriberRequest $request, User $user) {

        try {

             $subscribe = (new SubscribeEntityPayment($user))
                ->purpose('Subscribe')
                ->payload(['plan_id' => $request->plan_id])
                ->isTransaction()
                ->historyType(HistoryType::SUBSCRIPTION)
                ->pay(new PaymentHandler($request->paymentMethod()))
                ->thenReturn();

             app(NewNotificationAction::class)->execute($subscribe, NotificationType::SUBSCRIPTION, $subscribe->owner, user());

             $subscribe->owner->notify(
                 (new SubscribeMailNotification($subscribe))->locale($subscribe->owner->locale)
             );

             app(TrackFnsAction::class)->handler([TrackFnType::SUBSCRIBE], \user()->external_tracker_id, Plan::find($request->plan_id)->monthly_cost);


            return $this->respondWithSuccess('Ok');

        }catch (PaymentFailedException $exception) {

            return $this->respondError(

                app(ErrorHandlerAction::class)->handler($exception->getMessage())
            );
        }


    }
}
