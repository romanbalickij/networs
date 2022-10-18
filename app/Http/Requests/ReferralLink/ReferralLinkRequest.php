<?php

namespace App\Http\Requests\ReferralLink;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReferralLinkRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }

    public function payload() {

        return collect($this->validated())
            ->merge([
                'user_id' => Auth::id()
            ])
            ->toArray();
    }
}
