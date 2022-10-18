<?php


namespace App\Services\DataTransferObjects;


use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class PlanDto extends DataTransferObject
{
    /** @var string|null */
    public $name;

    /** @var int|null */
    public $monthly_cost;

    /** @var int|null */
    public $discount;

    /** @var null|string */
    public $description;


    public static function fromRequest(Request $request) {

        return new self([
            'name'         => $request->get('name'),
            'monthly_cost' => $request->get('monthly_cost'),
            'discount'     => $request->get('discount'),
            'description'  => $request->get('description'),
        ]);
    }
}
