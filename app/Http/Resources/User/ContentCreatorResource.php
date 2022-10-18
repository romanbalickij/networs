<?php

namespace App\Http\Resources\User;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ContentCreatorResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {

        $isMySubscribed = Auth::check() ? user()->isMySubscribed($this) : false;

        return array_merge($this->filtrateFields(

            (new UserResource($this))->only(...array_merge($this->getFillable(), [ 'plans', 'reactions_count', 'id', 'posts_count', 'posts_media_count', 'video_count', 'image_count']))->resolve()

        ),
            [
                'i_am_blocked'        => $this->when(Auth::check(), fn () => $this->isBlockedUser(user())),
                'is_my_subscriber'    => $this->when(Auth::check(), fn () => $isMySubscribed),
                'i_am_subscriber'     => $this->when(Auth::check(), fn () => user()->isMySubscription($this)),
                'is_bookmarked'       => $this->when(Auth::check(), fn () => $this->isBookmarked()),
                'subscribers_count'   => $this->subscribers_count,

                  $this->mergeWhen(Auth::check() and $isMySubscribed, function () {

                      $subscriber = user()->getMySubscribe($this);

                      return [
                         'subscriber_group_id' => $subscriber->subscriber_group_id,
                         'subscriber_id'       => $subscriber->id
                      ];
                  })
            ]
        );
    }
}
