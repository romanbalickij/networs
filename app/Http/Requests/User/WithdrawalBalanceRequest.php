<?php

namespace App\Http\Requests\User;

use App\Enums\InvoiceType;
use App\Enums\WithdrawType;
use App\Rules\ConfirmedWithdraw;
use App\Rules\WithdrawBalance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WithdrawalBalanceRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'sum'            => ['required', new WithdrawBalance],
           // 'code'         => ['required', new ConfirmedWithdraw],
            'payment_type'   => 'required|'.Rule::in(WithdrawType::CREDIT_CARD, WithdrawType::CRYPTOCURRENCY),
            'crypto_type'    => 'required_if:payment_type,==,crypto|'. Rule::in( WithdrawType::CRYPTO_TYPE_TRON, WithdrawType::CRYPTO_TYPE_ETHEREUM),
            'crypto_address' => "required_if:payment_type,==,crypto|string"
        ];
    }
}
