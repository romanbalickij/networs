<?php


namespace App\Services\Actions;


use App\Models\AccountManager;
use App\Models\User;

class AddAccountManagerAction
{

    public function execute($data) :void{

       $search = $data['key'];

       $user = User::where($search, $data[$search])->firstOrFail();

         AccountManager::updateOrCreate([
            'user_id'         => $user->id,
            'manages_user_id' => $data['manages_user_id']
        ]);
    }
}
