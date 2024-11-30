<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Test;

use Orchestra\Testbench\TestCase as TestBenchTestCase;
use Sourcetoad\Logger\LoggerFacade;
use Sourcetoad\Logger\LoggerServiceProvider;

class TestCase extends TestBenchTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LoggerServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'logger' => LoggerFacade::class,
        ];
    }
}
