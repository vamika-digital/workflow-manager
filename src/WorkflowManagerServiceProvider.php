<?php

namespace VamikaDigital\WorkflowManager;

use Illuminate\Support\ServiceProvider;

class WorkflowManagerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'vamikadigital');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'vamikadigital');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/workflowmanager.php', 'workflowmanager');

        // Register the service the package provides.
        $this->app->singleton('workflowmanager', function ($app) {
            return new WorkflowManager;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['workflowmanager'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/workflowmanager.php' => config_path('workflowmanager.php'),
        ], 'workflowmanager.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/vamikadigital'),
        ], 'workflowmanager.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/vamikadigital'),
        ], 'workflowmanager.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/vamikadigital'),
        ], 'workflowmanager.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
