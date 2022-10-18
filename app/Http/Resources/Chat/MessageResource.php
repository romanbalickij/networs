<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\Attachment\AttachmentCollection;
use App\Http\Resources\Reaction\ReactionCollection;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
{
    use Filtratable;

    public function toArray($request = null)
    {
        return $this->filtrateFields([
            'id'            => $this->id,
            'chat_id'       => $this->chat_id,
            'text'          => $this->text,
            'meta'          => $this->meta,
            'is_ppv'        => $this->is_ppv,
            'ppv_price'     => $this->ppv_price,
            'is_me'         => $this->isMe(),
            'is_bookmarked' => $this->isBookmarked(),
            'read'          => $this->isReadMessage(),
            'created'       => $this->created_at,
            'is_pay'        => $this->isPayFor(Auth::id()),

            'reactions'    => (new ReactionCollection($this->whenLoaded('reactions'))),
            'media'        => (new AttachmentCollection($this->whenLoaded('media'))),
            'others'       => (new AttachmentCollection($this->whenLoaded('others'))),
        ]);
    }
}
