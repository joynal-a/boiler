<?php

namespace Abedin\Boiler\Providers;

use Abedin\Boiler\Commands\MakeAuth;
use Abedin\Boiler\Commands\MakeModel;
use Illuminate\Support\ServiceProvider;

class BoilerServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerNewModelCommand();
        $this->registerMakeAuthCommand();
    }

    protected function registerNewModelCommand(): void
    {
        $this->app->bind('command.make:model', MakeModel::class);
        $this->commands(['command.make:model']);
    }

    protected function registerMakeAuthCommand()
    {
        $this->app->bind('command.auth:generate', MakeAuth::class);
        $this->commands(['command.auth:generate']);
    }
}