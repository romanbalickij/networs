<?php

namespace App\Http\Resources\Post;

use App\Enums\ReactionType;
use App\Http\Resources\Attachment\AttachmentCollection;
use App\Http\Resources\Reaction\ReactionCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {

        return $this->filtrateFields([
            'id'                   => $this->id,
            'text'                 => $this->text,
            'access'               => $this->access,
            'interested'           => $this->when(Auth::id() == $this->user_id, $this->interested),
            'clickthroughs'        => $this->when(Auth::id() == $this->user_id, $this->clickthroughs),
            'shows'                => $this->when(Auth::id() == $this->user_id, $this->shows),
            'reactions_heart_count'=> $this->countReactionHeart(),
            'reactions_fire_count' => $this->countReactionFire(),
            'is_ppv'               => $this->is_ppv,
            'is_pinned'            => $this->is_pinned,
            'ppv_price'            => $this->ppv_price,
            'visible_after'        => $this->visible_after,
            'visible_until'        => $this->visible_until,
            'created'              => $this->created_at,
            'comment_count'        => $this->commentCount(),
            'is_reaction_fire'     => $this->isHasReactionFor(Auth::id(), ReactionType::TYPE_FIRE),
            'is_reaction_heart'    => $this->isHasReactionFor(Auth::id(), ReactionType::TYPE_HEART),
            'is_bookmarked'        => $this->whenLoaded('bookmarks', fn() => $this->isBookmarked()),
            'is_pay'               => $this->unlockFor(Auth::id()),
            'ppv_earned'           => $this->when(Auth::id() == $this->user_id, $this->ppv_earned),
            'ppv_user_paid'        => $this->when(Auth::id() == $this->user_id, $this->ppv_user_paid),
            'is_me'                => $this->isMy(),


            'creator'   => (new UserResource($this->whenLoaded('user')))->only('id', 'name', 'surname', 'avatar', 'nickname'),
            'media'     => (new AttachmentCollection($this->whenLoaded('media'))),
            'others'    => (new AttachmentCollection($this->whenLoaded('others')))->except('poster'),
            'reactions' => (new ReactionCollection($this->whenLoaded('reactions'))),

        ]);
    }
}
