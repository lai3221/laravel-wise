<?php

namespace Lai3221\LaravelWise\Providers;

use Lai3221\LaravelWise\Client;
use Lai3221\LaravelWise\Services\BalanceService;
use Illuminate\Support\ServiceProvider;

class WiseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the configuration
        $this->mergeConfigFrom(__DIR__ . '/../../config/wise.php', 'wise');

        // Register the main client
        $this->app->singleton(Client::class, function ($app) {
            return new Client();
        });

        // Register the balance service
        $this->app->singleton(BalanceService::class, function ($app) {
            return new BalanceService($app->make(Client::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish the configuration
        $this->publishes([
            __DIR__ . '/../../config/wise.php' => config_path('wise.php'),
        ], 'wise-config');
    }
} 