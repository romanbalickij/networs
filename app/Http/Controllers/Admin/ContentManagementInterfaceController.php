<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Admin\Content\InterfaceTextCollection;
use App\Http\Resources\Admin\Content\InterfaceTextResource;
use App\Models\InterfaceText;
use App\Models\Page;
use App\Services\Actions\UpdateInterfaceTextAction;
use Illuminate\Http\Request;

class ContentManagementInterfaceController extends BaseController
{

    public function __invoke(Page $page) {

        return $this->respondWithSuccess(

            new InterfaceTextCollection($page->interfaceTexts)
        );
    }

    public function update(InterfaceText $interfaceText, Request $request, UpdateInterfaceTextAction $updateInterfaceTextAction) {

        $content = $updateInterfaceTextAction->execute($interfaceText, $request->all(), $request->file('images'));

        return $this->respondWithSuccess(

            new InterfaceTextResource($content->load('images'))
        );
    }
}
