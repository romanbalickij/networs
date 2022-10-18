<?php

namespace App\Http\Controllers\Api;

use App\Enums\HistoryType;
use App\Enums\TrackFnType;
use App\Exceptions\PaymentFailedException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Donation\DonateStoreRequest;
use App\Models\User;
use App\Services\Actions\TrackFnsAction;
use App\Services\Payments\PaymentHandler\Entity\DonationEntityPayment;
use App\Services\Payments\PaymentHandler\PaymentHandler;

class DonationController extends BaseController
{

    public function store(DonateStoreRequest $request)
    {
        try {

            (new DonationEntityPayment(User::find($request->creator_id)))
                ->purpose('Donate')
                ->payload(['sum' => $request->get('sum')])
                ->isTransaction()
                ->historyType(HistoryType::DONATION)
                ->pay(new PaymentHandler($request->paymentMethod()));

            app(TrackFnsAction::class)->handler([TrackFnType::DONATE, TrackFnType::CONFIRM], \user()->external_tracker_id, $request->get('sum'));

            return $this->respondWithSuccess('Success');

        }catch (PaymentFailedException $exception) {

            return $this->respondError($exception->getMessage());
        }

    }
}
