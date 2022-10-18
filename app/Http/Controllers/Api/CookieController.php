<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;

use App\Http\Resources\Cookie\CookieCollection;
use App\Http\Resources\Cookie\CookieContentCollection;
use App\Models\Cookie;
use App\Models\CookieContent;

class CookieController extends BaseController
{

    public function index() {

        $cookies = Cookie::all();

        $blocks = CookieContent::all();

        return $this->respondWithSuccess([
            'cookies' => new CookieCollection($cookies),
            'blocks'  => new CookieContentCollection($blocks)
        ]);
    }
}
