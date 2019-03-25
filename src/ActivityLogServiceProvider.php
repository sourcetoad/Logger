<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger;

use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/logger.php', 'activity-logger');
    }

    public function register()
    {
        //
    }
}