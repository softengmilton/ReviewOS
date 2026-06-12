<?php

namespace App\Providers;

use App\Models\Organization;
use App\Models\Post;
use App\Observers\OrganizationObserver;
use App\Observers\PostObserver;
use App\Support\CurrentOrganization;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CurrentOrganization::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Organization::observe(OrganizationObserver::class);
        Post::observe(PostObserver::class);

        Vite::prefetch(concurrency: 3);
    }
}
