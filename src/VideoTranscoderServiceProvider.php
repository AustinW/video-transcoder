<?php

namespace Austinw\VideoTranscoder;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class VideoTranscoderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/video-transcoder.php' => config_path('video-transcoder.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/video-transcoder'),
            ], 'views');

            if (! class_exists('CreatePackageTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_video_transcoder_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_video_transcoder_table.php'),
                ], 'migrations');
            }
        }

        Route::middlewareGroup('video-transcoder', config('video-transcoder.middleware', []));

        $this->registerRoutes();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'video-transcoder');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/video-transcoder.php', 'video-transcoder');
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }

    /**
     * Get the Telescope route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration()
    {
        return [
            'domain' => config('video-transcoder.domain', null),
//            'namespace' => 'AustinW\VideoTranscoder\Http\Controllers',
            'prefix' => config('video-transcoder.path'),
            'middleware' => 'video-transcoder',
        ];
    }
}
