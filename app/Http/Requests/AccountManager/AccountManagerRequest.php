<?php

namespace App\Http\Requests\AccountManager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AccountManagerRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'email'   => 'email',
            'surname' => 'string'
        ];
    }

    public function payload() {

        return collect($this->validated())
            ->merge([
                'manages_user_id' => Auth::id(),
                'key'             => collect(...$this->keys())->first()
            ])
            ->toArray();
    }
}
