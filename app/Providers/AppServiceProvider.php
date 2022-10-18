<?php

namespace App\Providers;

use App\Contracts\Blocked;
use App\Enums\FileType;
use App\Jobs\ImageBlurJob;
use App\Mixins\ArchivedZipMacros;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\Donation;
use App\Models\File;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Post;
use App\Models\PostClickthroughHistory;
use App\Models\PostInterestHistory;
use App\Models\PostShowHistory;
use App\Models\ReferralLink;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Actions\QueueEventSendAction;
use App\Services\BlockedService;
use App\Services\Payments\CryptoInterface;
use App\Services\Payments\Gateways\CryptoPayment;
use App\Services\Payments\Gateways\StripeGateway;
use App\Services\Payments\PaymentGateway;
use BeyondCode\ServerTiming\Facades\ServerTiming;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;
use Stripe\Stripe;
use Illuminate\Support\Facades\Queue;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

class AppServiceProvider extends ServiceProvider
{
    private int $queryId = 0;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $environment = env('APP_ENV') == 'dev' ? 'dev' : 'production';

        if ($this->app->environment($environment)) {
            $this->app['request']->server->set('HTTPS', true);
        }

        $this->app->bind(Blocked::class, BlockedService::class);

        $this->app->singleton(PaymentGateway::class, function () {
             return new StripeGateway();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Queue::after(function (JobProcessed $event) {

            app(QueueEventSendAction::class)->handler($event);
        });


        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'post'                    => Post::class,
            'message'                 => Message::class,
            'subscription'            => Subscription::class,
            'donation'                => Donation::class,
            'postShowHistory'         => PostShowHistory::class,
            'postClickthroughHistory' => PostClickthroughHistory::class,
            'postInterestHistory'     => PostInterestHistory::class,
            'comment'                 => Comment::class,
            'referralLink'            => ReferralLink::class,
            'chat'                    => Chat::class,
            'user'                    => User::class,
            'attachment'              => File::class,
            'invoice'                 => Invoice::class,
        ]);

        JsonResource::withoutWrapping();

        Filesystem::mixin(new ArchivedZipMacros());

        Stripe::setApiKey(config('services.stripe.secret'));

        if (env('USE_PROFILING_TRACE')) {
            DB::listen(function ($query) {
                $previous = ServerTiming::getDuration('Database') ?? 0;
                ServerTiming::setDuration('Database', $previous+$query->time);

                // $query->sql
                ServerTiming::setDuration("Query ".++$this->queryId, $query->time);
            });
        }
    }
}
