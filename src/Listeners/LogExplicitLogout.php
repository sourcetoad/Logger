<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Listeners;

use Illuminate\Auth\Events\Logout;

class LogExplicitLogout
{
    public function handle(Logout $event)
    {
        //
    }
}