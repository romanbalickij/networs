<?php

namespace App\Http\Resources\Notification;

use App\Enums\NotificationType;
use App\Http\Resources\Notification\Account\AccountNotificationResource;
use App\Http\Resources\Notification\Comment\CommentNotificationResource;
use App\Http\Resources\Notification\Donation\DonationNotificationResource;
use App\Http\Resources\Notification\Message\MessageNotificationResource;
use App\Http\Resources\Notification\Post\PostNotificationResource;
use App\Http\Resources\Notification\Subscription\SubscriptionNotificationResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {

        return  [
            $this->mergeWhen($this->entity_type == NotificationType::REACTION_POST,   new PostNotificationResource($this) ),
            $this->mergeWhen($this->entity_type == NotificationType::REACTION_MESSAGE,new MessageNotificationResource($this) ),
            $this->mergeWhen($this->entity_type == NotificationType::SUBSCRIPTION,    new SubscriptionNotificationResource($this) ),
            $this->mergeWhen($this->entity_type == NotificationType::DONATION,        new DonationNotificationResource($this) ),
            $this->mergeWhen($this->entity_type == NotificationType::UNREAD_MESSAGES, new MessageNotificationResource($this) ),
            $this->mergeWhen($this->entity_type == NotificationType::COMMENT,         new CommentNotificationResource($this) ),

            $this->mergeWhen( NotificationType::ACCOUNT_VERIFIED,                      new AccountNotificationResource($this) ),
            $this->mergeWhen($this->entity_type == NotificationType::ACCOUNT_BLOCKED,  new AccountNotificationResource($this) ),
            $this->mergeWhen($this->entity_type == NotificationType::ACCOUNT_UNBLOCKED,new AccountNotificationResource($this) ),
            $this->mergeWhen($this->entity_type == NotificationType::ACCOUNT_COMMENT_MODERATED, new CommentNotificationResource($this) ),
        ];
    }
}
