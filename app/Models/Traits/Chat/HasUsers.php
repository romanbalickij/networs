<?php

namespace App\Models\Traits\Chat;


use App\Models\User;

trait HasUsers
{
    public function user() {

        return $this->belongsTo(User::class, 'service_id');
    }

    public function creator() {

        return $this->belongsTo(User::class, 'client_id');
    }

    public function hasUser($user) :bool {

        return ($this->client_id === $user->id or $this->service_id === $user->id);
    }

    public function contrepartyUser() {

        return $this->service_id == user()->id
            ? $this->creator
            : $this->user;
    }

}
