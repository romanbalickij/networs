<?php

namespace App\Models;


use App\Models\Traits\User\HasAccountManagers;
use App\Models\Traits\User\HasAdCampaigns;
use App\Models\Traits\User\HasBackground;
use App\Models\Traits\User\HasBanned;
use App\Models\Traits\User\HasBlockedUsers;
use App\Models\Traits\User\HasBookmark;
use App\Models\Traits\User\HasChats;
use App\Models\Traits\User\HasDonations;
use App\Models\Traits\User\HasHistories;
use App\Models\Traits\User\HasInvoices;
use App\Models\Traits\User\HasLanding;
use App\Models\Traits\User\HasMessages;
use App\Models\Traits\User\HasNotifications;
use App\Models\Traits\User\HasPayments;
use App\Models\Traits\User\HasPlans;
use App\Models\Traits\User\HasPosts;
use App\Models\Traits\User\HasProfile;
use App\Models\Traits\User\HasProfilePhoto;
use App\Models\Traits\User\HasReferralLinks;
use App\Models\Traits\User\HasSettings;
use App\Models\Traits\User\HasSubscriberGroups;
use App\Models\Traits\User\HasSubscriptions;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use App\Services\Builders\UserBuilder;
use App\Services\Collection\UserCollection;
use App\Services\Interaction\Interactionable;
use App\Traits\UploadFileTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail, JWTSubject
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasBanned,
        HasProfilePhoto,
        HasBackground,
        UploadFileTrait,
        HasProfile,

        //relationship operation
        HasPayments,
        HasPosts,
        HasMessages,
        HasChats,
        HasInvoices,
        HasDonations,
        HasReferralLinks,
        HasBlockedUsers,
        HasBookmark,
        HasNotifications,
        HasSubscriberGroups,
        HasPlans,
        HasSubscriptions,
        HasAdCampaigns,
        HasAccountManagers,
        HasSettings,
        Interactionable,
        HasHistories,
        HasLanding;



    protected $fillable = [
        'name',
        'email',
        'password',
        'surname',
        'location',
        'avatar',
        'background',
        'url',
        'locale',
        'phone',
        'address',
        'business_address',
        'tax_number',
        'role',
        'activity_status',
        'description',
        'balance',
        'verified',
        'blocked',
        'is_online',
        'referral_link_id',
        'count_subscribers',
        'current_account',
        'email_verified_at',
        'quality',
        'nickname',
        'external_tracker_id',
        'confirmed_code',
        'ui_prompts'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'        => 'datetime',
        'verified'                 => 'boolean',
        'is_online'                => 'boolean',
        'blocked'                  => 'boolean',
        'verified_payment'         => 'boolean',
        'posts_sum_reaction_count' => 'integer',
        'count_subscribers'        => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {

            $model->setDefaultSettings();
        });
    }

    public function identities() {

        return $this->hasMany(SocialIdentity::class);
    }

    public function interactions() {

        return $this->hasMany(Interaction::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(
            (new VerifyEmail)->locale($this->locale)
        );
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(
            (new ResetPassword($token))->locale($this->locale)
        );
    }

    public function getFullNameAttribute() {

        return $this -> name .' '. $this->surname;
    }

    public function getNicknameAttribute($value) {

        return '@'. Str::replace(' ', '_', $value);
    }

    public function setNicknameAttribute($value)
    {
        $this->attributes['nickname'] = Str::replace(' ', '_', $value);
    }
    public function newEloquentBuilder($query)
    {
        return new UserBuilder($query);
    }

    public function newCollection(array $models = [])
    {
        return new UserCollection($models);
    }

}
