<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Traits;

use Illuminate\Support\Arr;
use Sourcetoad\Logger\LoggerServiceProvider;

trait HasRelationships
{
    public static function getActualClassNameForMorph($class): string
    {
        return Arr::get(LoggerServiceProvider::$morphs ?: [], $class, $class);
    }
}
