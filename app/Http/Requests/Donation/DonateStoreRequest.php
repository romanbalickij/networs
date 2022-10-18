<?php

namespace App\Http\Requests\Donation;

use App\Models\PaymentMethod;
use App\Rules\DonationRule;
use Illuminate\Foundation\Http\FormRequest;

class DonateStoreRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'creator_id'        => 'required|integer',
            'sum'               => ['required','integer', new DonationRule],
            'payment_method_id' => 'required|integer|exists:payment_methods,id'
        ];
    }

    public function paymentMethod() {

        return PaymentMethod::find($this->payment_method_id);
    }
}
