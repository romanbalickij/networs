<?php

namespace App\Models;

use App\Enums\BookmarkType;
use App\Enums\InteractionType;
use App\Models\Traits\Bookmark\HasUser;
use App\Services\Builders\BookmarkBuilder;
use App\Services\Collection\BookmarkCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bookmark extends Model
{
    use HasFactory,

        HasUser;

    protected $fillable = [
      'user_id',
      'entity_id',
      'entity_type',
    ];

    public function newEloquentBuilder($query)
    {
        return new BookmarkBuilder($query);
    }

    public function newCollection(array $models = [])
    {
        return new BookmarkCollection($models);
    }

    public function remove() {

        $this->delete();
    }

    public function entity() {

        return $this->morphTo();
    }

    public function file() {

        return $this->belongsTo(File::class, 'entity_id');
    }

    public function post() {

        return $this->belongsTo(Post::class, 'entity_id');
    }

    public function message() {

        return $this->belongsTo(Message::class, 'entity_id');
    }

    public function user() {

        return $this->belongsTo(User::class, 'entity_id');
    }

}
