<?php

namespace App\Http\Requests\General;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class UnlockRequest extends FormRequest
{

    public function rules()
    {
        return [
            'payment_method_id' => 'required|integer|exists:payment_methods,id'
        ];
    }

    public function paymentMethod() {

        return PaymentMethod::find($this->payment_method_id);
    }
}
