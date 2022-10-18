<?php


namespace App\Models\Traits\User;


use App\Enums\UserType;
use App\Models\User;
use App\Services\Actions\UserCalculateRatingAction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait HasProfile
{
    public function online():void {

        $this->update(['is_online' => true]);
    }

    public function offline():void {

        $this->update(['is_online' => false]);
    }

    public function isOnline() {

        return (bool) $this->is_online;
    }

    public function isAccountVerified() {

        return $this->verified;
    }

    public function isEmailVerified() {

        return (bool) $this->email_verified_at;
    }

    public function verifyAccount() :void {

        $this->update(['verified' => true]);
    }

    public function emailVerify() {

        $this->update(['email_verified_at' => \Carbon\Carbon::now()]);
    }

    public function blockedAccount() :void {

        $this->update(['blocked' => true]);
    }

    public function unblockedAccount() :void {

        $this->update(['blocked' => false]);
    }

    public function switchRole($role) :void {

        $this->update(['role' => $role]);
    }

    public function getCreatedAtAttribute($value) {

        return Carbon::parse($value)->format('d-m-Y');
    }

    public function userId() {

        return $this->id;
    }

    public function isAdmin() : bool
    {
        return $this->role == UserType::ADMIN;
    }

    public function myRating() {

        return app(UserCalculateRatingAction::class)->handler($this);
    }

    public function generatePassword($password) {

        if(!$password) {
            return;
        }
        $this->update(['password' => Hash::make($password)]);
    }

    public function generateConfirmedCode() {

        $this->update(['confirmed_code' => Str::random(8)]);
    }

    public function isMyProfile(User $author) : bool
    {
        return $this->id === $author->id;
    }

    public function generateNickname() {

        $this->update(['nickname' => Str::of($this->fullName)->append("_N{$this->id}")]);
    }

    public function deleteProfile() {

        $this->posts->each(function ($post) {
            $post->deleteReactions();
            $post->deleteBookmarks();
            $post->deleteAttachments();
            $post->delete();
        });
        $this->deleteProfilePhoto();
        $this->deleteBackground();
        $this->delete();
    }

    public function getLocaleAttribute($value) {

        return $value == null ? $this->defaultLocale() : $value;
    }

    protected function defaultLocale() {

        return 'en';
    }

    public function hasExternalTrackerId() {

        return (bool) $this->external_tracker_id;
    }

    public function confirmedWithdraw($code) {
          if(!$code) {
              return false;
          }

         $is = $this->confirmed_code == $code;

         $this->update(['confirmed_code' => null]);

         return $is;
    }

}
