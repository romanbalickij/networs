<?php

namespace App\Http\Resources\Attachment;

use App\Http\Resources\Chat\BroadcastNewMessageResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BroadcastAttachmentCollection extends ResourceCollection
{
    use Filtratable;

    protected $user;

    //public $collects = BroadcastNewMessageResource::class;

    public function __construct($resource, $userId = null)
    {
        $this->user = $userId;

        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return $this->map(function ($file){

                return [
                    'id'        => $file->id,
                    'mime_type' => $file->mime_type,
                    'name'      => $file->name,
                    'url'       => fileUrl($file->allowedBroadCastFileTo($this->user) ? $file->url : $file->blur),
                    'poster'    => fileUrl($file->allowedBroadCastFileTo($this->user) ? $file->poster : $file->blur),
                    'is_bookmarked' =>  $file->isBookmarked(),
                ];
        });
    }
}
