<?php


namespace App\Services\Actions;


use App\Enums\ChatType;
use App\Enums\FileType;
use App\Models\File;
use App\Services\Chat\ConversationInterface;
use App\Services\DataTransferObjects\ChatDto;
use Illuminate\Support\Facades\Auth;


class GroupSendMessageAction
{

    public function handler(ChatDto $chatDto, $attachments, ConversationInterface $conversation) :void {

       user()->load(['subscribers' => function($query) use ($chatDto) {

          isset($chatDto->group_id)
              ? $query->whereGroup($chatDto->group_id)
              : null;
        }]);

       user()->subscribers->each(

           fn ($subscriber) => $this->execute($attachments, $conversation, $chatDto, $subscriber)
       );

       // a copy was made based on these files, so they are not needed
       File::destroy($attachments);
    }

    protected function execute($attachments, $conversation, $chatDto, $subscriber) :void {

        $chat = $conversation->createRoom(user(), $subscriber->user_id);

        $message = app(SendMessageAction::class)->execute(
            $chat,
            array_merge($chatDto->toArray(), ['user_id' => Auth::id()])
        );

       // $message->addAttachments($attachments); old fix 1

        if(isset($attachments) and count($attachments) > 0) {
          //  app(AttachFileAction::class)->execute($message->id, $attachments, FileType::MODEL_MESSAGE); old fix 2

            $files = File::find($attachments);

            $files->each(function ($file) use($message) {

                $newFile            = $file->replicate();
                $newFile->type      = FileType::MODEL_MESSAGE;
                $newFile->entity_id = $message->id;
                $newFile->save();
            });
        }


    }
}
