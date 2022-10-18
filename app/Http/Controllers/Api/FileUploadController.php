<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\FileFailedException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\File\FileUploadRequest;
use App\Http\Resources\Attachment\AttachmentResource;
use App\Services\Actions\ErrorHandlerAction;
use App\Services\Actions\SaveSteamAttachmentAction;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends BaseController
{

    public function upload(FileUploadRequest $request, SaveSteamAttachmentAction $saveSteamAttachmentAction) {


        try {
            if(env('APP_ENV') == 'production') {
                $entity = $saveSteamAttachmentAction->addURl(
                    $request->get('main'),
                    $request->get('type'),
                    $request->get('blur'),
                    $request->get('poster'),
                    $request->file('other'),
                );

            }else {
                $entity = $saveSteamAttachmentAction->addFile(
                    $request->file('main'),
                    $request->file('blur'),
                    $request->file('poster'),
                );

            }

            return $this->respondWithSuccess(
                $entity->id
            );

        }catch (FileFailedException $exception) {

            return $this->respondError(

                app(ErrorHandlerAction::class)->handler($exception->getMessage())
            );
        }
    }
}
