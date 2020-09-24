<?php

namespace VamikaDigital\WorkflowManager;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use VamikaDigital\WorkflowManager\Traits\EventMap;

class WorkflowManagerServiceProvider extends ServiceProvider
{
  use EventMap;
  /**
   * Perform post-registration booting of services.
   *
   * @return void
   */
  public function boot()
  {
    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'vamikadigital');
    // $this->loadViewsFrom(__DIR__.'/../resources/views', 'vamikadigital');
    // $this->loadRoutesFrom(__DIR__.'/routes.php');
    $this->registerEvents();
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
    $this->mergeConfigFrom(__DIR__ . '/../config/workflowmanager.php', 'workflowmanager');

    // // Register the service the package provides.
    // $this->app->singleton('workflowmanager', function ($app) {
    //     return new WorkflowManager;
    // });
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
      __DIR__ . '/../config/workflowmanager.php' => config_path('workflowmanager.php'),
    ], 'workflowmanager.config');

    $this->publishes([
      __DIR__ . '/../database/migrations/' => database_path('migrations'),
    ], 'migrations');

    // Publishing the translation files.
    $this->publishes([
      __DIR__ . '/../resources/lang' => resource_path('lang/vendor/vamikadigital'),
    ], 'workflowmanager.translations');

    // Publishing the views.
    /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/vamikadigital'),
        ], 'workflowmanager.views');*/

    // Publishing assets.
    /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/vamikadigital'),
        ], 'workflowmanager.views');*/

    // Registering package commands.
    // $this->commands([]);
  }

  /**
   * Register the Laraflow global events for the future usage.
   */
  protected function registerEvents()
  {
    $events = $this->app->make(Dispatcher::class);

    foreach ($this->events as $event => $listeners) {
      foreach ($listeners as $listener) {
        $events->listen($event, $listener);
      }
    }
  }
}
