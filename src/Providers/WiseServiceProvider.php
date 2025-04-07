<?php

namespace Lai3221\LaravelWise\Providers;

use Illuminate\Support\ServiceProvider;
use Lai3221\LaravelWise\Client;
use Lai3221\LaravelWise\Services\BalanceService;
use Lai3221\LaravelWise\Services\ProfileService;
use Lai3221\LaravelWise\Services\QuoteService;
use Lai3221\LaravelWise\Services\RecipientService;
use Lai3221\LaravelWise\Services\TransferService;

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
        $this->app->bind(Client::class, function ($app) {
            return new Client();
        });

        // Register the balance service
        $this->app->bind(BalanceService::class, function ($app) {
            return new BalanceService($app->make(Client::class));
        });

        // Register the profile service
        $this->app->bind(ProfileService::class, function ($app) {
            return new ProfileService($app->make(Client::class));
        });

        // Register the recipient service
        $this->app->bind(RecipientService::class, function ($app) {
            return new RecipientService($app->make(Client::class));
        });

        // Register the quote service
        $this->app->bind(QuoteService::class, function ($app) {
            return new QuoteService($app->make(Client::class));
        });
        // Register the transfer service
        $this->app->bind(TransferService::class, function ($app) {
            return new TransferService($app->make(Client::class));
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
