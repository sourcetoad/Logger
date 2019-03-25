<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Listeners;

use Illuminate\Auth\Events\Lockout;

class LogLockedLogins
{
    public function handle(Lockout $event)
    {
        //
    }
}