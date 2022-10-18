<?php

namespace App\Http\Controllers\Landing;

use App\Http\Resources\Landing\PageCollection;
use App\Models\Page;

class LandingController extends PageController
{

    public function index() {

       $pages = Page::query()
           ->with(['interfaceTexts.images'])
           ->landing()
           ->get();

       return $this->respondWithSuccess(

           (new PageCollection($pages))->except('name')
       );
    }
}
