<?php


namespace App\Services;


use App\Models\ReferralLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

trait Referralable
{
    public static function bootReferralable()
    {

        static::saving(function (ReferralLink $model) {

            $model->name = $model->generateReferral($model->getAttribute('name'));
        });
    }

    protected function generateReferral($string) {

       $user = Crypt::encrypt(Auth::id());

       return Str::of($string)
           ->append("?referral=$user");
    }
}
