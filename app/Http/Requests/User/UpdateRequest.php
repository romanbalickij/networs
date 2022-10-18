<?php

namespace App\Http\Requests\User;


use App\Enums\UserType;
use App\Services\DataTransferObjects\UserDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'email'            => ['sometimes', 'email',  Rule::unique('users')->ignore($this->user()->id)],
            'password'         => 'sometimes',
            'avatar'           => env('APP_ENV') == 'production' ? 'string' : 'image|mimes:jpeg,png,jpg,gif,svg',
            'background'       => env('APP_ENV') == 'production' ? 'string' : 'image|mimes:jpeg,png,jpg,gif,svg',
            'name'             => 'sometimes',
            'surname'          => 'sometimes',
            'description'      => 'sometimes',
            'location'         => 'sometimes',
            'url'              => 'sometimes',
            'activity_status'  =>  Rule::in(UserType::ACTIVE, UserType::BUSY,  UserType::INACTIVE),
            'locale'           => 'sometimes',
            'phone'            => 'regex:/^([0-9\s\-\+\(\)]*)$/',
            'address'          => 'sometimes',
            'business_address' => 'sometimes',
            'tax_number'       => 'sometimes',
            'referral_link_id' => 'sometimes|int',
            "nickname"         => ['sometimes', Rule::unique('users')->ignore($this->user()->id)],
            'ui_prompts'       => 'string'
        ];
    }

    public function getDto() {

        return $this->validated();

        return app(UserDto::class)->fromRequest($this);
    }
}
