<?php

namespace App\Http\Resources\User;


use App\Http\Resources\Post\PostCollection;
use App\Traits\Filtratable;
use http\Exception\RuntimeException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'                => $this->id,
            'name'              => $this->name,
            'surname'           => $this->surname,
            'email'             => $this->email,
            'nickname'          => $this->nickname,
            'location'          => $this->location,
            'avatar'            => $this->profilePhotoUrl,
            'background'        => $this->background,
            'url'               => $this->url,
            'locale'            => $this->locale,
            'phone'             => $this->phone,
            'address'           => $this->address,
            'role'              => $this->role,
            'activity_status'   => $this->activity_status,
            'description'       => $this->description,
            'is_online'         => $this->isOnline(),
            'verified'          => $this->verified,
            'reactions_count'   => $this->posts_sum_reaction_count,
            'posts_count'       => $this->posts_count,
            'posts_media_count '=> $this->posts_sum_media_count,
            'video_count'       => $this->posts_sum_video_count,
            'image_count'       => $this->posts_sum_image_count,

            'is_bookmarked'    => $this->whenLoaded('bookmarked', fn() => $this->isBookmarked()),

            'plans'            => (new PlanCollection($this->whenLoaded('plans'))),
        ]);
    }



}
