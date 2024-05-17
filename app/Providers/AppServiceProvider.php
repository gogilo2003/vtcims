<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once (app_path('Support/helpers.php'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $viewNamespace = env('VIEW_NAMESPACE', 'default');

        // Register the namespace
        view()->addNamespace('custom', resource_path("views/$viewNamespace"));

    }
}
