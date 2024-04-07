<?php

namespace App\Providers;

use App\Services\ResponseService;
use App\Services\YoutubeHelperService;
use App\Services\YoutubeUrlService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('response', function () {
            return new ResponseService();
        });

        $this->app->singleton('youtubeHelper', function () {
            return new YoutubeHelperService();
        });

        $this->app->singleton('youtubeUrl', function () {
            return new YoutubeUrlService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
