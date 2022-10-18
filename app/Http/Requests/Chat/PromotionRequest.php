<?php

namespace App\Http\Requests\Chat;

use App\Services\DataTransferObjects\ChatDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PromotionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'text'        => 'string',
            'attachments' => 'array',
        ];
    }

    public function getDto() {

        return collect(
            app(ChatDto::class)->fromRequest($this)->toArray()
        )
            ->except('attachments')
            ->merge([
                'user_id' => Auth::id(),
                'is_ppv'  => false
            ])
            ->toArray();
    }
}
