<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\PaymentFailedException;
use App\Http\Controllers\BaseController;
use App\Http\Resources\User\PaymentMethodCollection;
use App\Http\Resources\User\PaymentMethodResource;
use App\Models\PaymentMethod;
use App\Services\Actions\ErrorHandlerAction;
use App\Services\Payments\PaymentGateway;
use BeyondCode\ServerTiming\Facades\ServerTiming;
use Illuminate\Http\Request;

class PaymentMethodController extends BaseController
{

    public function __construct(
        public PaymentGateway $paymentGateway,

    ){
        $this->paymentGateway = $paymentGateway;
    }

    public function index() {
        $methods = user()->paymentMethods;

        return $this->respondWithSuccess(

            new PaymentMethodCollection($methods)
        );
    }

    public function store(Request $request) {

      try {

          $card = $this->paymentGateway->withUser(user())
              ->createCustomer()
              ->addCard($request->token, $request->name);

      }catch (PaymentFailedException $exception) {

          return $this->respondError(
              app(ErrorHandlerAction::class)->handler($exception->getMessage())
          );
      }

      return $this->respondWithSuccess(

          new PaymentMethodResource($card)
      );

    }

    public function destroy(PaymentMethod $paymentMethod) {

        try {

            app(PaymentGateway::class)
                ->withUser(user())
                ->getCustomer()
                ->deleteCard($paymentMethod);

        }catch (PaymentFailedException $exception){

            return $this->respondError(

                app(ErrorHandlerAction::class)->handler($exception->getMessage())
            );
        }

        return $this->respondWithSuccess('Ok');
    }
}
