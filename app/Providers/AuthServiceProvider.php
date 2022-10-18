<?php

namespace App\Providers;

use App\Models\AdCampaign;
use App\Models\File;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Post;
use App\Policies\CampaignPolicy;
use App\Policies\FilePolicy;
use App\Policies\PagePolicy;
use App\Policies\PlanPolicy;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Post::class       => PostPolicy::class,
         Plan::class       => PlanPolicy::class,
         Page::class       => PagePolicy::class,
         AdCampaign::class => CampaignPolicy::class,
         File::class       => FilePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
