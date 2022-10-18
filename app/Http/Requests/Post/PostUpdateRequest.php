<?php

namespace App\Http\Requests\Post;

use App\Enums\PostType;
use App\Services\DataTransferObjects\PostDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'text'          => 'required|string',
            "access"        =>  Rule::in(PostType::PUBLIC, PostType::PRIVATE),
//            'visible_until' => 'date'
        ];
    }

    public function getDto() {

        return app(PostDto::class)->fromRequest($this);
    }
}
