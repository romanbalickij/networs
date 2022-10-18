<?php

namespace App\Models;


use App\Enums\ChatType;
use App\Models\Traits\Chat\HasMessages;
use App\Models\Traits\Chat\HasUsers;
use App\Services\Builders\ChatBuilder;
use App\Services\Collection\ChatCollection;
use App\Services\Interaction\Interactionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory,

        HasUsers,
        Interactionable,
        HasMessages;

    protected $fillable = [
        'mode',
        'service_id',
        'client_id'
    ];


    public function newEloquentBuilder($query)
    {
        return new ChatBuilder($query);
    }

    public function isSupport() {

        return $this->mode === ChatType::ADMIN;
    }

    public function newCollection(array $models = [])
    {
        return new ChatCollection($models);
    }

    public function touchLastReply() {
        $this->updated_at = now();
        $this->save();
    }

}
