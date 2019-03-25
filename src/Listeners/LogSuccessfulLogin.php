<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Listeners;

use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        //
    }
}