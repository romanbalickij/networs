<?php

namespace App\Http\Resources\Chat;

use App\Enums\EventType;
use App\Http\Resources\Attachment\AttachmentCollection;
use App\Http\Resources\Attachment\BroadcastAttachmentCollection;
use App\Http\Resources\Reaction\ReactionCollection;
use App\Http\Resources\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class BroadcastNewMessageResource extends JsonResource
{
    use Filtratable;

    protected $user;

    public function __construct($resource, $userId = null)
    {
        $this->user = $userId;

        parent::__construct($resource);
    }

    public function toArray($request = null)
    {
        return $this->filtrateFields([
            'id'            => $this->id,
            'chat_id'       => $this->chat_id,
            'text'          => $this->text,
            'meta'          => $this->meta,
            'is_ppv'        => $this->is_ppv,
            'ppv_price'     => $this->ppv_price,
            'is_me'         => false,
            'is_bookmarked' => false,
            'read'          => false,
            'created'       => $this->created_at,
            'is_pay'        => false,
            'sender'        => (new UserResource($this->owner))->only('id', 'name', 'surname', 'avatar', 'nickname'),

            'reactions'     => (new ReactionCollection($this->whenLoaded('reactions'))),
            'media'         => (new BroadcastAttachmentCollection($this->whenLoaded('media'), $this->user)),
            'others'        => (new BroadcastAttachmentCollection($this->whenLoaded('others'), $this->user)),

        ]);
    }
}
