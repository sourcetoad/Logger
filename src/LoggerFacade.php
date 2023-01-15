<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger;

use Illuminate\Support\Facades\Facade;

class LoggerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'logger';
    }
}
