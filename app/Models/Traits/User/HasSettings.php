<?php

namespace App\Models\Traits\User;


use App\Enums\SettingType;
use App\Models\UserSetting;

trait HasSettings
{

    public function userSettings() {

        return $this->hasMany(UserSetting::class);
    }

    public function getSetting($key) {

        return $this->searchSetting($key);
    }

    public function getAllSettings() {

        return $this->userSettings;
    }

    public function setSetting($key, $value)
    {
        $this->storeSetting($key, $value);

    }

    public function setSettings($data = [])
    {
        foreach($data as $key=> $setting)
        {
            $value = $setting;
            $this->storeSetting($key, $value);
        }

    }

    public function setting($key) {

       return (bool) optional($this->userSettings
           ->filter(fn($setting) => $setting->key == $key)
           ->first())->value;
    }

    private function storeSetting($key, $value)
    {
        $record = UserSetting::where(['user_id' => $this->id, 'key' => $key])->first();
        if($record)
        {
            $record->value = $value;
            $record->save();
        } else {
            $data = new UserSetting(['key' => $key, 'value' => $value]);
            $this->userSettings()->save($data);
        }
    }

    private function searchSetting($key)
    {
        $data = UserSetting::where(['user_id' => $this->id, 'key' => $key])->first(['key','value']);
        if($data) {
            return $data;
        }
        return null;
    }

    public function setDefaultSettings() {

        $this->setSettings([
            'theme'                     => 'dark',
            'page_post_visibility'      => 'public',

            'display_online_status'     => true,
            'display_subscriber_number' => true,
            'auto_prolong_subscription' => true,

            //email preferences:
            'reaction'          => true,
            'subscription'      => true,
            'donation'          => true,
            'unread_message'    => true,
            'comment_response'  => true,
            'invoice'           => true,
            'promotion'         => true,
        ]);
    }
}
