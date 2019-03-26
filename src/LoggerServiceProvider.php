<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    protected $listeners = [
        \Illuminate\Auth\Events\Login::class => [
            \Sourcetoad\Logger\Listeners\LogSuccessfulLogin::class,
        ],
        \Illuminate\Auth\Events\Failed::class => [
            \Sourcetoad\Logger\Listeners\LogFailedLogin::class,
        ],
        \Illuminate\Auth\Events\Logout::class => [
            \Sourcetoad\Logger\Listeners\LogExplicitLogout::class,
        ],
        \Illuminate\Auth\Events\Lockout::class => [
            \Sourcetoad\Logger\Listeners\LogLockedLogins::class,
        ],
        \Illuminate\Auth\Events\PasswordReset::class => [
            \Sourcetoad\Logger\Listeners\LogLockedLogins::class,
        ],
    ];

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/logger.php', 'logger');
        $this->publishes([
            __DIR__  . '/../config/logger.php' => config_path('logger.php')
        ], 'config');
    }

    public function register()
    {
        $this->app->singleton(Logger::class, function() {
            return new Logger();
        });

        $this->app->alias(Logger::class, 'logger');

        $this->registerEventListeners();
        $this->registerMorphMaps();
    }

    private function registerMorphMaps()
    {
        $morphables = config('logger.morphs');
        Relation::morphMap($morphables, true);
    }

    private function registerEventListeners()
    {
        foreach ($this->listeners as $key => $listeners) {
            foreach ($listeners as $listener) {
                \Event::listen($key, $listener);
            }
        }
    }
}