<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Listeners;

use Illuminate\Auth\Events\Logout;
use Sourcetoad\Logger\Logger;

class LogExplicitLogout
{
    public function handle(Logout $event): void
    {
        resolve(Logger::class)->logExplicitLogout();
    }
}
