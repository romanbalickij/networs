<?php


namespace App\Services\Actions;


class SetExternalTrackerAction
{

    public function execute($user, null|string|int $external_tracker_id = null) {

        if(!$external_tracker_id) {
            return;
        }

        if($user->hasExternalTrackerId()) {
            return;
        }

        $user->update([
           'external_tracker_id' => $external_tracker_id
        ]);
    }
}
