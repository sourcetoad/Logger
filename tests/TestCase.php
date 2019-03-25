<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Test;

use Sourcetoad\Logger\LoggerFacade;
use Sourcetoad\Logger\LoggerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LoggerServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'logger' => LoggerFacade::class
        ];
    }
}