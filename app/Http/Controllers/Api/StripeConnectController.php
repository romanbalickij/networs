<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;

use App\Models\User;
use App\Services\Payments\PaymentGateway;
use Illuminate\Http\Request;

class StripeConnectController extends BaseController
{

    public function store(Request $request) {

          $token = app(PaymentGateway::class)
                ->connect($request->code);

          $user = User::find($request->get('state'));

          $user->setPaymentAccount($token->stripe_user_id);

          //todo add redirect to profile ;
          return $this->respondWithSuccess('Ok');
    }
}
