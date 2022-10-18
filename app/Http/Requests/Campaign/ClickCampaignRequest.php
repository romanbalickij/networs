<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ClickCampaignRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            //
        ];
    }

    public function getPayload() {

        return collect([
            'user_agent' => $this->server('HTTP_USER_AGENT'),
            'user_ip'    => $this->ip(),
            'user_id'    => Auth::id()
        ])->toArray();
    }
}
