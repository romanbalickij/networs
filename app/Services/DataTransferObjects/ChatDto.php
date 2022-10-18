<?php


namespace App\Services\DataTransferObjects;


use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class ChatDto extends DataTransferObject
{

    /** @var string|null */
    public $text;

    /** @var integer|null|string|bool */
    public $is_ppv;

    /** @var integer|null|string */
    public $ppv_price;

    /** @var integer|null|string */
    public $group_id;


    public static function fromRequest(Request $request) {

        return new self([
            'text'      => $request->get('text'),
            'is_ppv'    => $request->get('is_ppv'),
            'ppv_price' => $request->get('ppv_price'),
            'group_id'  => $request->get('group_id'),
        ]);
    }

    public static function fromArray(array $array) {
        return new self(array_filter(
            $array,
            fn ($key) => in_array($key, ['text', 'is_ppv', 'ppv_price', 'group_id']),
            ARRAY_FILTER_USE_KEY
        ));
    }
}
