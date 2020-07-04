<?php

namespace Austinw\VideoTranscoder;

use Illuminate\Support\ServiceProvider;
use Austinw\VideoTranscoder\Commands\VideoTranscoderCommand;

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

            $this->commands([
                VideoTranscoderCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'video-transcoder');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/video-transcoder.php', 'video-transcoder');
    }
}
