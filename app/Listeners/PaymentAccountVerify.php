<?php

namespace App\Listeners;

use App\Models\GatewayPayment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Models\WebhookCall;

class PaymentAccountVerify
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * todo stripe-webhooks https://dashboard.stripe.com/test/webhooks/we_1HlwUiKgGhTBjWMtFlBsWJqM change uri
     * @param  object  $event
     * @return void
     */
    public function handle(WebhookCall $webhookCall)
    {
        $stripe = $webhookCall->payload['data']['object'];

        $account = GatewayPayment::where('account_id', $stripe['id'])->first();

        if($account) {
            $account->approved();

            Log::info("Account verified successfully for  #id ".$account->id);

        }else {

            Log::info("AccountStripeVerify - Something went wrong, Account not verify, not found GatewayPayment or else... ".$stripe['id']);
        }
    }
}
