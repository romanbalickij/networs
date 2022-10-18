<?php

namespace App\Http\Requests\Post;

use App\Enums\ReactionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReactPostRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reaction'   => 'required|string',
            'type'       => 'required'
        ];
    }


    public function reactPostPayload() {

        return collect($this->validated())
            ->merge([
                'entity_type' => ReactionType::MODEL_POST,
                'user_id'     => Auth::id()
            ])
            ->toArray();
    }
}
