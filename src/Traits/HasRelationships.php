<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Traits;

use Illuminate\Support\Arr;
use Sourcetoad\Logger\LoggerServiceProvider;

trait HasRelationships
{
    /**
     * Retrieve the actual class name for a given morph class.
     *
     * @param  string  $class
     * @return string
     */
    public static function getActualClassNameForMorph($class)
    {
        return Arr::get(LoggerServiceProvider::$morphs ?: [], $class, $class);
    }
}