<?php

namespace App\Models;

use App\Services\Builders\InteractionBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function interactionable()
    {
        return $this->morphTo();
    }

    public function newEloquentBuilder($query)
    {
        return new InteractionBuilder($query);
    }

}
