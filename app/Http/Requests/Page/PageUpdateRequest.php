<?php

namespace App\Http\Requests\Page;

use App\Services\DataTransferObjects\PageDto;
use Illuminate\Foundation\Http\FormRequest;

class PageUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name'                => 'string',
            'title.en'            => 'string',
            'title.ru'            => 'string',
            'meta_description.en' => 'string',
            'meta_description.ru' => 'string',
            'robots'              => 'string',
            'meta_tags.en'        => 'string',
            'meta_tags.ru'        => 'string',
            'type'                => 'string',
            'body.en'             => 'string',
            'body.ru'             => 'string'
        ];
    }

    public function payload() {

        return $this->validated();
    }
}
