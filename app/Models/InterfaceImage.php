<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InterfaceImage extends Model
{
    use HasFactory;

    /** @noinspection  */
    protected $fillable = [
        'name',
        'url'
    ];


    public function getUrlAttribute($value) {

        return fileUrl($value);
    }
}
