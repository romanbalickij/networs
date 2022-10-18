<?php

namespace App\Http\Requests\Post;

use App\Enums\PostType;
use App\Services\DataTransferObjects\PostDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
            'is_ppv'        => 'sometimes',
            'ppv_price'     => 'integer',
            'attachments'   => 'array',
            'visible_until' => 'sometimes'
        ];
    }

    public function getDto() {

        return $this->validated();

        return app(PostDto::class)->fromRequest($this);
    }
}
