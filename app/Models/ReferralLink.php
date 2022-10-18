<?php

namespace App\Models;

use App\Models\Traits\ReferralLink\HasUser;
use App\Services\Builders\ReferralLinkBuilder;
use App\Services\Referralable;
use App\Services\History\Historyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralLink extends Model
{
    use HasFactory,
        Referralable,

        Historyable,
        HasUser;

    protected $fillable = [
        'user_id',
        'name',
    ];


    public function newEloquentBuilder($query)
    {
        return new ReferralLinkBuilder($query);
    }

    protected static function actionHistoryDelete() {

        return true;
    }

    protected static function actionHistoryCreate() {
        // not use
        return false;
    }

}
