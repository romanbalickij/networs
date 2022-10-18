<?php

namespace App\Http\Requests\Campaign;

use App\Services\DataTransferObjects\CampaignDto;
use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'   => 'required|string'
        ];
    }

    public function getDto() {

        return app(CampaignDto::class)->fromRequest($this);
    }
}
