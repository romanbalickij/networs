<?php


namespace App\Stateful\Behaviours;


use App\Enums\EventType;
use App\Models\Message;
use App\Models\Post;
use App\Services\Actions\MessageReadAction;
use App\Services\Actions\PostClickAction;
use App\Stateful\Behaviour;
use App\Stateful\EventProtocol;
use Illuminate\Support\Arr;

/**
 * Class HandlesClick
 * @package App\Stateful\Behaviours
 *
 *
 * Request:
 * {
 *   id: 12345
 * }
 *
 */
class HandlesClick extends Behaviour
{

    public function on (): array
    {
        return [EventType::EVENT_FROM_FRONTEND_CLICK_POST];
    }

     /**
      * Models Messages  id
      **/
    public function triggered (\App\Stateful\EventProtocol $protocol)
    {
        $post = Post::findOrFail($protocol->getPayload()['id']);

      //  $user = $protocol->getSenderConnection()->getUser();

        app(PostClickAction::class)->execute($post);
    }
}
