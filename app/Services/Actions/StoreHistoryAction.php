<?php


namespace App\Services\Actions;

use App\Models\History;


class StoreHistoryAction
{

    public function action(int $creator, string $type, int $entity, string|int|null|float $body) :void {

        History::create([
            'historyable_type' => $type,
            'historyable_id'   => $entity,
            'body'             => $body,
            'user_id'          => $creator,
            'action'           => 'created',
        ]);
    }
}
