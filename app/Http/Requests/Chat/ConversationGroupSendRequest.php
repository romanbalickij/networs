<?php

namespace App\Http\Requests\Chat;

use App\Services\DataTransferObjects\ChatDto;
use Illuminate\Foundation\Http\FormRequest;

class ConversationGroupSendRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'text'        => 'required|string',
         //   'is_ppv'      => 'required|integer',
            'ppv_price'   => 'integer',
            'attachments' => 'array',
            'group_id'    => 'sometimes|integer|exists:subscriber_groups,id'
        ];
    }

    public function getDto() {

        return app(ChatDto::class)->fromRequest($this);
    }
}
