<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Listeners;

use Illuminate\Auth\Events\Lockout;
use Sourcetoad\Logger\Logger;

class LogLockedLogins
{
    public function handle(Lockout $event)
    {
        resolve(Logger::class)->logLockedLogin();
    }
}