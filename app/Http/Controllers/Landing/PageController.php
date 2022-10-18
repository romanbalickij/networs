<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Landing\InterfaceTextResource;
use App\Http\Resources\Landing\PageCollection;
use App\Http\Resources\Landing\PageResource;
use App\Models\Page;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/documentation",
     *     description="Home page",
     *     @OA\Response(response="default", description="Welcome page")
     * )
     */

    public function page(Page $page) {

        return $this->respondWithSuccess(

            new PageResource($page)
        );
    }

    public function index() {

        $pages = Page::content()->get();

        return $this->respondWithSuccess(

            new PageCollection($pages)
        );
    }

    public function pageError($pageKey) {

        $page = Page::where('key', $pageKey) -> firstOrFail();

        return $this->respondWithSuccess(

            (new InterfaceTextResource($page->interfaceTexts()->first()))->only('name', 'text')
        );
    }
}
