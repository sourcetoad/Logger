<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Listeners;

use App\PasswordReset;
use Sourcetoad\Logger\Logger;

class LogPasswordReset
{
    public function handle(PasswordReset $passwordReset)
    {
        resolve(Logger::class)->logPasswordReset(\Auth::user());
    }
}