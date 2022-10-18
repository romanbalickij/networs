<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseHelpers;

class BaseController extends Controller
{
    use ApiResponseHelpers;

    public function perPage() {

        return config('app.paginate_limit');
    }
}
