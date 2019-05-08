<?php

namespace Omatech\AutoFaker;

use Illuminate\Support\ServiceProvider;
use AutoFaker;

class AutoFakerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AutoFaker::class, function ($app) {
            return new AutoFaker(config('autofaker'));
        });

        $this->publish();
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }

    private function publish()
    {
        $this->publishes([
            __DIR__ . '/../../config/fake_record_format.yaml' => config_path('/autofaker/fake_record_format.json'),
            __DIR__ . '/../../config/index.yaml' => config_path('/autofaker/index.yaml'),
            __DIR__ . '/../../resources/views' => resource_path('/views/markup'),
        ], 'autofaker-publish');
    }
}