<?php

namespace App\Http\Requests\Chat;


use App\Enums\NotificationType;
use App\Enums\ReactionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReactMessageRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'reaction' => 'required',
            'type'     => 'required'
        ];
    }

    public function reactMessagePayload() {

        return collect($this->validated())
            ->merge(['entity_type' => ReactionType::MODEL_MESSAGE])
            ->toArray();
    }
}
