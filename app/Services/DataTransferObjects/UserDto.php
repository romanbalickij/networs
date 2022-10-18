<?php

namespace App\Services\DataTransferObjects;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class UserDto extends DataTransferObject
{

    /** @var string|null */
    public $email;

    /** @var string|null */
    public $password;

    /** @var string|null */
    public $name;

    /** @var string|null */
    public $surname;

    /** @var string|null */
    public $description;

    /** @var string|null */
    public $location;

    /** @var string|null */
    public $url;

    /** @var string|null */
    public $activity_status;

    /** @var string|null */
    public $locale;

    /** @var integer|null */
    public $balance;

    /** @var string|null */
    public $phone;

    /** @var string|null */
    public $nickname;


    /** @var string|null */
    public $address;

    /** @var string|null */
    public $business_address;

    /** @var string|null */
    public $tax_number;

    /** @var integer|null */
    public $referral_link_id;

    public static function fromRequest(Request $request) {

        return new self([
            'email'            => $request->get('email'),
            'password'         => $request->get('password'),
            'name'             => $request->get('name'),
            'surname'          => $request->get('surname'),
            'description'      => $request->get('description'),
            'location'         => $request->get('location'),
            'url'              => $request->get('url'),
            'activity_status'  => $request->get('activity_status'),
            'locale'           => $request->get('locale') ,
            'balance'          => $request->get('balance'),
            'phone'            => $request->get('phone'),
            'address'          => $request->get('address'),
            'business_address' => $request->get('business_address'),
            'tax_number'       => $request->get('tax_number'),
            'referral_link_id' => $request->get('referral_link_id'),
            'nickname'         => $request->get('nickname'),
        ]);
    }

}
