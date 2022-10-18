<?php

namespace App\Models;

use App\Enums\PageType;
use App\Models\Traits\Page\HasInterfaceTexts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory,
        HasTranslations,

        HasInterfaceTexts;

    protected $fillable = [
        'key',
        'name',
        'title',
        'meta_description',
        'robots',
        'slug',
        'meta_tags',
        'type',
        'body'
    ];

    public $translatable = [
        'title',
        'body',
        'meta_description',
        'meta_tags',
    ];

    public function scopeLanding($query) {

        return $query->whereIn('key',[
                PageType::LANDING_HEADER,
                PageType::LANDING_FOOTER,
                PageType::LANDING_CONTENT,
                PageType::LANDING_KPI
            ]
        );
    }

    public function scopeContent($query) {

        return $query->whereNotIn('key',[
                PageType::LANDING_HEADER,
                PageType::LANDING_FOOTER,
                PageType::LANDING_CONTENT,
                PageType::LANDING_KPI,

                PageType::CONTENT_PAGE_400,
                PageType::CONTENT_PAGE_500
            ]
        );
    }

    public function scopeCustomer($query) {

        return $query->where('type', PageType::CUSTOM);
    }

    public function scopeLandingCreator($query) {

        return $query->where('key', PageType::LANDING_CREATOR);
    }

    public function scopeWhereLetterKey($query, $key) {

        return $query->where('key', $key);
    }
}
