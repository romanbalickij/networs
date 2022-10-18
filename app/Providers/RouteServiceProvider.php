<?php

namespace App\Providers;


//use App\WebSockets\Router;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */

    public function boot()
    {

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(function ($router){
                    require base_path('routes/api.php');
                   // require base_path('routes/users.php');
                });


            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));


            $this->mapAuthRoutes();

            $this->mapLandingRoutes();

            $this->mapProfileRoutes();

            $this->mapUserRoutes();

            $this->mapInvoiceRoutes();

            $this->mapSubscriberRoutes();

            $this->mapSubscriptionRoutes();

            $this->mapSubscriberGroupRoutes();

            $this->mapConversationsRoutes();

            $this->mapAttachmentsRoutes();

            $this->mapPostsRoutes();

            $this->mapAdminRoutes();
        });

//        $this->app->singleton('websockets.router', function () {
//            return new Router();
//        });

    }

    protected function mapAuthRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace.'\Auth')
            ->group(base_path('routes/auth.php'));
    }

    protected function mapLandingRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace.'\Landing')
            ->group(base_path('routes/landing.php'));
    }

    protected function mapProfileRoutes()
    {
        Route::prefix('api/profile')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/profile.php'));
    }

    protected function mapUserRoutes()
    {
        Route::prefix('/api/users/')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/users.php'));
    }

    protected function mapInvoiceRoutes()
    {
        Route::prefix('api/invoices')
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/invoices.php'));
    }

    protected function mapSubscriberRoutes()
    {
        Route::prefix('api/subscribers')
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/subscribers.php'));
    }

    protected function mapSubscriptionRoutes()
    {
        Route::prefix('api/subscriptions')
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/subscriptions.php'));
    }

    protected function mapSubscriberGroupRoutes()
    {
        Route::prefix('api/subscriberGroup')
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/subscriberGroup.php'));
    }

    protected function mapConversationsRoutes()
    {
        Route::prefix('api/conversations')
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/conversations.php'));
    }

    protected function mapAttachmentsRoutes()
    {
        Route::prefix('api/attachments')
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/attachments.php'));
    }

    protected function mapPostsRoutes()
    {
        Route::prefix('api/posts')
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/posts.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::prefix('api/admin')
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }



    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(500)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
