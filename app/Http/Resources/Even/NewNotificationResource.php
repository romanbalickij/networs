<?php

namespace App\Http\Resources\Even;

use App\Enums\NotificationType;
use App\Http\Resources\Chat\MessageResource;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Donation\DonationResource;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Subscription\SubscriptionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NewNotificationResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            $this->mergeWhen($this->entity_type == NotificationType::UNREAD_MESSAGES,   new MessageResource($this->message)),
            $this->mergeWhen($this->entity_type == NotificationType::REACTION_POST,     new PostResource($this->post)),
            $this->mergeWhen($this->entity_type == NotificationType::REACTION_MESSAGE,  new MessageResource($this->message)),
            $this->mergeWhen($this->entity_type == NotificationType::SUBSCRIPTION,      new SubscriptionResource($this->subscription)),
            $this->mergeWhen($this->entity_type == NotificationType::DONATION,          new DonationResource($this->donation)),
            $this->mergeWhen($this->entity_type == NotificationType::COMMENT,           new CommentResource($this->comment)),
        ];
    }
}
