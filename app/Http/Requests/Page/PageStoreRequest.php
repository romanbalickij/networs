<?php

namespace App\Http\Requests\Page;

use App\Enums\PageType;
use Illuminate\Foundation\Http\FormRequest;

class PageStoreRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name'             => 'string',
            'title'            => 'required|array',
            'meta_description' => 'array',
            'robots'           => 'string',
            'meta_tags'        => 'array',
            'body'             => 'array'
        ];
    }

    public function payload() {

        return collect($this->validated())
            ->merge(['type' => PageType::CUSTOM])
            ->toArray();
    }
}
