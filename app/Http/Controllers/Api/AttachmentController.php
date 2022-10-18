<?php

namespace App\Http\Controllers\Api;

use App\Enums\BookmarkType;
use App\Enums\FileType;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Attachment\AttachmentResource;
use App\Models\File;
use App\Services\Actions\AddBookmarkAction;
use finfo;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class AttachmentController extends BaseController
{

    public function __invoke(File $file) {

        $file->load([
            'entity.payments.invoice'
        ]);

        return $this->respondWithSuccess(

            new AttachmentResource($file)
        );
    }

    public function download(File $file) {

        $this->authorize('download', $file);

        if(env('APP_ENV') === 'production' and $file->type != FileType::TYPE_OTHER) {

            $content = file_get_contents($file->url);

            $file_info = new finfo(FILEINFO_MIME_TYPE);

            $mime_type = $file_info->buffer(file_get_contents($file));

            $name = Str::random(30). '.' .Str::after($mime_type, '/');

        }else {
            $name = $file->name;

            $content = Storage::disk(config('app.use_file_disk'))->get($file->url);
        }


        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'. $name .'"',
        ];

        try{
            return Response::make(

                $content, 200, $headers
            );

        }catch (\Exception $exception) {

            return $this->respondError($exception->getMessage());
        }
    }

    public function bookmark(File $file, AddBookmarkAction $addBookmarkAction) {

        $addBookmarkAction->execute($file, BookmarkType::MODEL_ATTACHMENT);

        return $this->respondWithSuccess('Success');
    }
}
