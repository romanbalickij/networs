<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Page\PageStoreRequest;
use App\Http\Requests\Page\PageUpdateRequest;
use App\Http\Resources\Admin\Content\PageCollection;
use App\Http\Resources\Admin\Content\PageResource;
use App\Models\Page;
use App\Services\Actions\ContentUpdateAction;

class ContentManagementController extends BaseController
{

    public function index() {

       $pages = Page::all();

       return $this->respondWithSuccess(

            new PageCollection($pages)
       );
    }

    public function store(PageStoreRequest $request) {

        $page = Page::create($request->payload());

        return $this->respondWithSuccess(

            new PageResource($page)
        );
    }

    public function update(Page $page, PageUpdateRequest $request, ContentUpdateAction $contentUpdateAction) {

        return $this->respondWithSuccess(

            new PageResource($contentUpdateAction->execute($request->payload(), $page))
        );
    }

    public function delete(Page $page) {

        $this->authorize('delete', $page);

        $page->delete();

        return $this->respondWithSuccess('Ok');
    }
}
