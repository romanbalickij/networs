<?php


namespace App\Services\Interaction;

use App\Models\Interaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Interactionable
{

    public static function bootInteractionable()
    {
        self::deleted(function (Model $model) {

            $model->interactionable()->where('user_id', Auth::id())->delete();
        });
    }

    public function interactionable() {

        return $this->morphMany(Interaction::class,'interactionable');
    }

    public function pushToInteractions(string $type, $author) {

        if(!$this->hasInteractionable($type, $author)) {
            $this->interactionable()->create([
                'user_id' => $author->id,
                'type' => $type,
            ]);
        }
      }

   protected function hasInteractionable($type, $author) :bool {

        return $this->interactionable()
            ->where(fn($q) => $q->where('user_id', $author->id)->where('type', $type))->count();
   }
}
