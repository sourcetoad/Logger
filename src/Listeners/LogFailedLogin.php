<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Listeners;

use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function handle(Failed $event)
    {
        //
    }
}