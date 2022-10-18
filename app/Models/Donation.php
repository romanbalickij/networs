<?php

namespace App\Models;

use App\Models\Traits\Donation\HasNotifications;
use App\Models\Traits\Donation\HasUser;
use App\Services\History\Historyable;
use App\Services\Interaction\Interactionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory,

        HasUser,
        HasNotifications,
        Historyable,
        Interactionable;


    protected $fillable = [
      'user_id',    // who donate
      'creator_id', // main
      'sum',
    ];

    protected static function actionHistoryDelete() {

        return true;
    }

    protected static function actionHistoryCreate() {
        // not use
        return false;
    }
}
