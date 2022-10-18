<?php

namespace App\Http\Requests\User;


use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SwitchRoleRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'role' => 'required|'.Rule::in(UserType::ADMIN, UserType::USER)
        ];
    }
}
