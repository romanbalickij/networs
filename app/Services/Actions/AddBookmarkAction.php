<?php


namespace App\Services\Actions;


use App\Enums\InteractionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AddBookmarkAction
{

    public function execute(Model $model, $type): void {

        $this->setInteraction($model, $type);

        user()->toggleBookmarked($model, $type);

    }

    protected function setInteraction($model, $type) {

        if(user()->hasBookmarkedFor($model, $type)) {
            return;
        }

        user()->interactions()->create([
            'interactionable_type' => Str::lower(Str::afterLast(get_class($model), '\\')),
            'interactionable_id'   => $model->id,
            'type'                 => InteractionType::TYPE_BOOKMARKING,
        ]);
    }
}
