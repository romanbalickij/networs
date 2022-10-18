<?php


namespace App\Services\Actions;


use App\Enums\EventType;
use App\Enums\FileType;
use App\Http\Resources\Chat\BroadcastNewMessageResource;
use App\Jobs\ImageBlurJob;
use App\Stateful\Controllers\EventController;
use Illuminate\Queue\Events\JobProcessing;

class QueueEventSendAction
{

    public function handler($event) {

        $commandName = $event->job->payload()['data']['commandName'];

        if($commandName != ImageBlurJob::class) {
            return;
        }

        $entityModel  = unserialize($event->job->payload()['data']['command']);

        $file = $entityModel->entity;

        if($file->entity_type == FileType::MODEL_MESSAGE) {

            $message = $file->entity;

            $message->load([
                'reactions',
                'bookmarks',
                'media.entity.payments',
                'others.entity.payments',
                'media.bookmarks',
                'others.bookmarks',
            ]);

            $to = $message->getOtherUser($message->user_id);

            EventController::trigger($to, EventType::EVENT_FINISHED_LOADING_PICTURE, ['content' => new BroadcastNewMessageResource($message, $to)]);
        }
    }
}
