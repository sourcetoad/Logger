<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Sourcetoad\Logger\LoggerServiceProvider;
use Sourcetoad\Logger\Models\Relations\LoggerMorphTo;

trait HasRelationships
{
    public static function getActualClassNameForMorph($class): string
    {
        return Arr::get(LoggerServiceProvider::$morphs ?: [], $class, $class);
    }

    protected function newMorphTo(Builder $query, Model $parent, $foreignKey, $ownerKey, $type, $relation): MorphTo
    {
        return new LoggerMorphTo($query, $parent, $foreignKey, $ownerKey, $type, $relation);
    }
}
