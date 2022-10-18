<?php

namespace App\Http\Requests\Plan;

use App\Services\DataTransferObjects\PlanDto;
use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name'         => 'required|string',
            'monthly_cost' => 'required|numeric',
            'discount'     => 'numeric',
            'description'  => "sometimes"
        ];
    }


    public function getDto() {

        return app(PlanDto::class)->fromRequest($this);
    }
}
