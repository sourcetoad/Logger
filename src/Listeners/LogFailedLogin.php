<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Listeners;

use Illuminate\Auth\Events\Failed;
use Sourcetoad\Logger\Logger;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        resolve(Logger::class)->logFailedLogin();
    }
}
