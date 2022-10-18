<?php

namespace App\Models;

use App\Enums\FileType;
use App\Models\Traits\File\HasBookmark;
use App\Models\Traits\File\HasEntity;
use App\Services\Collection\FileCollection;
use App\Services\Interaction\Interactionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory,

        HasEntity,
        Interactionable,
        HasBookmark;

    protected $fillable = [
        'entity_type',
        'type',
        'entity_id',
        'mime_type',
        'url',
        'name',
        'poster',
        'blur'
    ];

    public function newCollection(array $models = [])
    {
        return new FileCollection($models);
    }

    public function entity() {

        return $this->morphTo();
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($message) {

            $message->deleteBookmarks();
        });
    }

    public function getUrlAttribute($value) {

          return $value;
    }

    public function getPosterAttribute($value) {

       return $value;
    }

    public function getPatchPosterAttribute($value) {

        return fileUrl(!$this->allowedFile() ? $this->blur : $this->poster);
    }

    public function getPatchFileAttribute($value) {

        if($this->type == FileType::TYPE_VIDEO and !$this->allowedFile()) {
            return NULL;
        }

        if($this->type == FileType::TYPE_OTHER ){
            return fileUrl($this->url, false);
        }

        return fileUrl(!$this->allowedFile() ? $this->blur : $this->url);
    }

    public function postPathFile($author, $user) {

        if(!$this->allowFromSubscribe($author, $user)) {
            return fileUrl($this->blur);
        }

        return $this->patchFile;
    }

    public function postPathPosterFile($author, $user) {

        if(!$this->allowFromSubscribe($author, $user)) {
            return fileUrl($this->blur);
        }

        return $this->patchPoster;
    }

    public function allowedBroadCastFileTo($userId) {

        if(!$this->isEntityPPV()) {
            return true;
        }

        return $this->isUnlockedFor($userId);
    }

    protected function fileDisk() {

        return config('app.use_file_disk');
    }

    protected function allowFromSubscribe($author, $subs) {

        if(isset($subs) and $author->isMyProfile($subs) ) {
            return true;
        }

       return $author->allowedPrivatePostFor($subs);
    }
}
