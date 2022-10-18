<?php

namespace App\Http\Resources\Admin\Content;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'               => $this->id,
        //    'key'              => $this->key,
            'name'             => $this->name,
            'title'            => $this->title,
            'meta_description' => $this->meta_description,
            'robots'           => $this->robots,
            'meta_tags'        => $this->meta_tags,
            'type'             => $this->type,
            'body'             => $this->body,
        ]);
    }
}
