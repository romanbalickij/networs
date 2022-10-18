<?php


namespace App\Services\Actions;

use App\Enums\InteractionType;
use App\Enums\NotificationType;
use App\Models\Donation;

class DonateAction
{

    public function handler($payload) :Donation {

       $donation = Donation::create($payload);

        // interactions Sent donations
       $donation->pushToInteractions(InteractionType::TYPE_SEND_DONATION, user());

        // interactions Received donations
       $donation->pushToInteractions(InteractionType::TYPE_RECEIVED_DONATION, $donation->owner);

       //send notification broadcast
       app(NewNotificationAction::class)->execute($donation, NotificationType::DONATION, $donation->owner, user());

       return $donation;
    }
}
