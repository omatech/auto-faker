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
    }
}