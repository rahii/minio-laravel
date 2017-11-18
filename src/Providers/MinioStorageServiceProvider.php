<?php

namespace Rahii\MinioLaravel\Providers;

use Illuminate\Support\ServiceProvider;

class MinioStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        // Publish configurations to config/pap-storage
        $this->publishes([
            __DIR__ . '/../config' => config_path(),
        ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /**
        * Merge configurations
        */
        /*
        $this->mergeConfigFrom(
            __DIR__ . '/config/app.php', 'packages.Rahii.MinioLaravel.app'
        );
        */

        $this->app->bind('ClassExample', function(){
            return $this->app->make('Rahii\MinioLaravel\Classes\ClassExample');
        });

    }
}
