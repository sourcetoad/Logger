<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Listeners;

use Illuminate\Auth\Events\PasswordReset;
use Sourcetoad\Logger\Logger;

class LogPasswordReset
{
    public function handle(PasswordReset $passwordReset): void
    {
        resolve(Logger::class)->logPasswordReset();
    }
}
