<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Chat\PromotionRequest;
use App\Services\Actions\PromotionMessage;
use App\Services\Actions\SendMessageAction;
use Illuminate\Http\Request;

class PromotionMessageController extends BaseController
{

    public function send(PromotionRequest $request, PromotionMessage $promotionMessage) {

        $promotionMessage->handler(
                app(SendMessageAction::class),
                $request->getDto(),
                $request->file('attachments')
            );

        return $this->respondWithSuccess('Ok');
    }
}
