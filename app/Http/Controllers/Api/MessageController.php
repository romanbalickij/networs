<?php

namespace App\Http\Controllers\Api;

use App\Enums\HistoryType;
use App\Enums\TrackFnType;
use App\Exceptions\PaymentFailedException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\General\UnlockRequest;
use App\Http\Resources\Chat\MessageResource;
use App\Models\Message;
use App\Services\Actions\ErrorHandlerAction;
use App\Services\Actions\MessageArrayReadAction;
use App\Services\Actions\MessageReadAction;
use App\Services\Actions\TrackFnsAction;
use App\Services\Payments\PaymentHandler\Entity\MediaEntityPPVPayment;
use App\Services\Payments\PaymentHandler\PaymentHandler;
use Illuminate\Http\Request;


class MessageController extends BaseController
{

    public function read(Message $message, MessageReadAction $messageReadAction) {

        $messageReadAction->execute($message);

        return $this->respondWithSuccess('Message read successfully');
    }

    public function reads(Request $request, MessageArrayReadAction $messageArrayReadAction) {

        $messageArrayReadAction->execute($request->messages);

        return $this->respondWithSuccess('Message read successfully');
    }

    public function support() {

        $user = \user()->load(['support.message']);

        return $this->respondWithSuccess(

            (new MessageResource($user->support->message))->only('id', 'text')
        );

    }

    public function unlock(Message $message, UnlockRequest $request) {

        try {

            (new MediaEntityPPVPayment($message))
                ->purpose('Unlock message')
                ->isTransaction()
                ->historyType(HistoryType::MESSAGE)
                ->pay(new PaymentHandler($request->paymentMethod()));

            $message->load([
                'media.entity.payments',
                'others.entity.payments',
                'reactions',
                'bookmarks',
            ]);

            app(TrackFnsAction::class)->handler([TrackFnType::PPV, TrackFnType::CONFIRM], \user()->external_tracker_id, $message->ppv_price);

            return $this->respondWithSuccess(

                new MessageResource($message)
            );

        }catch (PaymentFailedException $exception) {

            return $this->respondError(

                app(ErrorHandlerAction::class)->handler($exception->getMessage())
            );
        }

    }

    public function delete(Message $message) {

        $message->delete();

        return $this->respondOk('The Message deleted successfully');
    }
}
