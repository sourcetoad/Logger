<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Listeners;

use Illuminate\Auth\Events\Login;
use Sourcetoad\Logger\Logger;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        resolve(Logger::class)->logSuccessfulLogin();
    }
}