<?php

namespace AnalogRepublic\NovaDuo;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;
use AnalogRepublic\NovaDuo\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadJsonTranslationsFrom(lang_path('vendor/nova-duo'));

        $this->app->booted(function () {
            $this->routes();
        });

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/nova-duo.php' => config_path('nova-duo.php'),
            ], 'nova-duo.config');

        }
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authenticate::class, Authorize::class])
            ->prefix('nova-vendor/nova-duo')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nova-duo.php', 'nova-duo');
    }
}
