<?php

namespace App\Http\Resources\User;

use App\Models\Page;
use App\Models\User;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class LandingCreatorCollection extends ResourceCollection
{
    use Filtratable;

    public $creator;

    public function __construct($resource, $creator = null)
    {
        parent::__construct($resource);

        $this->creator = $creator;
    }

    public $collects = LandingCreatorResource::class;

    public function toArray($request)
    {

        return $this->filtrateFields([
             'landing'     => $this->processCollection($request),
             'interfaces'  => (new LandingCreatorTextResource(Page::query()->landingCreator()->first(), $this->creator))->resolve(),
             'creator'     => (new UserResource($this->creator))->only('id', 'name', 'surname')
        ]);

    }
}
