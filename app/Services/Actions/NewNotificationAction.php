<?php


namespace App\Services\Actions;

use App\Enums\EventType;
use App\Http\Resources\Even\NewNotificationResource;
use App\Http\Resources\User\UserResource;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Stateful\Controllers\EventController;
use Illuminate\Database\Eloquent\Model;

class NewNotificationAction
{

    public function execute(Model $model, string $type, User $to, User $from, $sendEvent = true) {

        $notification = Notification::create([
            'entity_type' => $type,
            'entity_id'   => $model->id,
            'user_id'     => $to->id,
            'client_id'   => $from->id,
            'read'        => false
        ]);

      if($sendEvent) {

          $notification->load([
              'post.user',
              'post.reactions',
              'post.bookmarks',
              'post.allComments:id,post_id',
              'post.others.entity.payments',
              'post.media.entity.payments',
              'post.media.bookmarks',
              'post.others.bookmarks',

              'message.bookmarks',
              'message.media.entity.payments',


              'subscription.user.userSettings',
              'subscription.plan',
              'subscription.subscriptionGroup',

              'donation',
              'comment.user',
              'comment.replies.user',
          ]);

          EventController::trigger($to->id, EventType::EVENT_NOTIFICATION, ['content' => [
              'id'          => $notification->id,
              'entity_type' => $notification->entity_type,
              'created'     => $notification->created_at,
              'sender'      => (new UserResource($from))->only('id', 'name', 'surname', 'avatar', 'nickname'),
              'entity'      => (new NewNotificationResource($notification))->resolve()
          ]]);

      }

      return $notification;
    }
}
