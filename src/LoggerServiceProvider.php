<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Sourcetoad\Logger\Commands\AuditModelResolver;
use Sourcetoad\Logger\Middleware\LogOutputtedKeys;

class LoggerServiceProvider extends ServiceProvider
{
    public static array $morphs = [];

    protected array $listeners = [
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
            \Sourcetoad\Logger\Listeners\LogPasswordReset::class,
        ],
    ];

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            $this->commands([AuditModelResolver::class]);
        }

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'logger');
        $this->mergeConfigFrom(__DIR__ . '/../config/logger.php', 'logger');
        $this->publishes([
            __DIR__  . '/../config/logger.php' => config_path('logger.php')
        ], 'logger');
    }

    public function register(): void
    {
        $this->app->singleton(Logger::class, function () {
            return new Logger();
        });

        $this->app->alias(Logger::class, 'logger');

        $this->registerEventListeners();
        $this->registerMorphMaps();
        $this->registerMiddleware();
    }

    private function registerMiddleware(): void
    {
        app(Kernel::class)->pushMiddleware(LogOutputtedKeys::class);
    }

    private function registerMorphMaps(): void
    {
        self::$morphs = config('logger.morphs', []);
    }

    private function registerEventListeners(): void
    {
        foreach ($this->listeners as $key => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($key, $listener);
            }
        }
    }
}
