<?php

namespace App\Providers;

use App\Services\SupabaseService;
use Illuminate\Support\ServiceProvider;

class SupabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SupabaseService::class, function ($app) {
            return new SupabaseService();
        });

        $this->app->alias(SupabaseService::class, 'supabase');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}