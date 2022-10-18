<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SetAccountManagedRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'managed_account_id' => 'required|integer'
        ];
    }
}
