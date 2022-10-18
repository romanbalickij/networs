<?php


namespace App\Stateful\Behaviours;


use App\Enums\EventType;
use App\Models\Post;
use App\Services\Actions\PostInterestAction;
use App\Stateful\Behaviour;
use App\Stateful\EventProtocol;
/**
 * Class HandlesInterest
 * @package App\Stateful\Behaviours
 *
 *
 * Request:
 * {
 *   id: 12345
 * }
 *
 */
class HandlesInterest extends Behaviour
{

    public function on (): array
    {
        return [EventType::EVENT_FROM_FRONTEND_INTEREST_POST];
    }

     /**
      * Models Messages  id
      **/
    public function triggered (\App\Stateful\EventProtocol $protocol)
    {
        $post = Post::findOrFail($protocol->getPayload()['id']);

      //  $user = $protocol->getSenderConnection()->getUser();

        app(PostInterestAction::class)->execute($post);

    }
}
