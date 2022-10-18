<?php

namespace App\Providers;

use App\Listeners\PaymentAccountVerify;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     * stripe-webhooks https://dashboard.stripe.com/test/webhooks/we_1HlwUiKgGhTBjWMtFlBsWJqM
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'stripe-webhooks::account.updated' => [
            PaymentAccountVerify::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
