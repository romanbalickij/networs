<?php

namespace App\Http\Requests\Subscriber;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class SubscriberRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_method_id' => 'integer|exists:payment_methods,id',
            'plan_id'           => 'required|integer'
        ];
    }

    public function paymentMethod() {

        return PaymentMethod::find($this->payment_method_id);
    }
}
