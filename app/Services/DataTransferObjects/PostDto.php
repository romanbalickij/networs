<?php

namespace App\Services\DataTransferObjects;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class PostDto extends DataTransferObject
{

    /** @var string|null */
    public $text;

    /** @var string|null */
    public $access;

    /** @var int|boolean|null|string */
    public $is_ppv;

    /** @var int|null|string */
    public $ppv_price;

    /** @var string|null */
    public $visible_after;

    /** @var string|null */
    public $visible_until;

    public static function fromRequest(Request $request) {

        return new self([
            'text'          => $request->get('text'),
            'access'        => $request->get('access'),
            'is_ppv'        => $request->get('is_ppv') ,
            'ppv_price'     => $request->get('ppv_price'),
            'visible_after' => $request->get('visible_after'),
            'visible_until' => $request->get('visible_until')
        ]);
    }

}
