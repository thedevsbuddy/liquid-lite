<?php

namespace Devsbuddy\LiquidLite\Providers;

use Devsbuddy\LiquidLite\LiquidLite;
use Devsbuddy\LiquidLite\ViewComposers\MenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class LiquidLiteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'liquid-lite');
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

         $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/config.php' => config_path('liquid-lite.php'),
            ], 'laravel-config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../../resources/views' => resource_path('views/vendor/liquid-lite'),
            ], 'laravel-views');

            // Publishing assets.
            $this->publishes([
                __DIR__ . '/../../resources/assets' => public_path('vendor/liquid-lite'),
            ], 'laravel-assets');

        }

        // Load helpers file
        if (file_exists(__DIR__ . '/../Http/helpers.php'))
        {
            require_once __DIR__ . '/../Http/helpers.php';
        }

        View::composer('liquid-lite::includes.sidebar', MenuComposer::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'liquid-lite');

        // Register the main class to use with the facade
        $this->app->singleton('liquid-lite', function () {
            return new LiquidLite;
        });

//        $this->commands($this->commands);
    }


}
