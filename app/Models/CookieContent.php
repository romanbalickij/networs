<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CookieContent extends Model
{
    use HasFactory,

        HasTranslations;

    protected $fillable = [
        'title',
        'body',
        'description',
        'footer_description',
        'key'
    ];

    protected $translatable = [
        'title',
        'body',
    ];
}
