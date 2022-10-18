<?php


namespace App\Services\DataTransferObjects;


use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class CampaignDto extends DataTransferObject
{

    /** @var string|null */
    public $name;

    public static function fromRequest(Request $request) {

        return new self([
            'name'  => $request->get('name'),
        ]);
    }
}
