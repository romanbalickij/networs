<?php

namespace App\Http\Requests\SubscriberGroup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SubscriberGroupRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'required|string'
        ];
    }

    public function payload() {

        return collect($this->validated())
            ->merge(['creator_id' => Auth::id()])
            ->toArray();
    }
}
