<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class CreatorSuggestionResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'surname'       => $this->surname,
            'avatar'        => $this->profilePhotoUrl,
            'background'    => $this->background,
            'description'   => $this->description,
            'nickname'      => $this->nickname,
            'verified'      => $this->verified,
            'is_bookmarked' => $this->isBookmarked()
        ];
    }
}
