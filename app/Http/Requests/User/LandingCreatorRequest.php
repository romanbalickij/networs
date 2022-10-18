<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LandingCreatorRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'main' => 'sometimes',
            'me'   => 'sometimes',
        ];
    }

    public function payload() {

        return collect($this->validated())
            ->merge(['user_id' => Auth::id()])
            ->toArray();
    }
}
