<?php

namespace App\Providers;

use App\Analytics\AnalyticsSourceManager;
use App\Analytics\Contracts\AnalyticsSourceFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AnalyticsSourceFactory::class, AnalyticsSourceManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
