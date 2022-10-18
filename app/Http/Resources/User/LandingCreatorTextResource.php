<?php

namespace App\Http\Resources\User;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class LandingCreatorTextResource extends JsonResource
{
    use Filtratable;

    protected $user;

    public function __construct($resource, $user = null)
    {
        parent::__construct($resource);

        $this->user = $user;
    }

    public function toArray($request)
    {
        $name = optional($this->user)->fullName;

        return $this->filtrateFields([
                'id'    => $this->id,
                'title' => str_replace('$username', "{$name}", $this->title),
                'text'  => $this->body,

        ]);
    }
}
