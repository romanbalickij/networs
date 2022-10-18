<?php

namespace App\Models;

use App\Models\Traits\InterfaceText\HasImages;
use App\Models\Traits\InterfaceText\HasPage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class InterfaceText extends Model
{
    use HasFactory,
        HasTranslations,
        HasImages,

        HasPage;

    protected $fillable = [
        'key',
        'page_id',
        'name',
        'length_limit',
        'text',
    ];

    public $translatable = [
        'text',
        'name',
    ];



}
