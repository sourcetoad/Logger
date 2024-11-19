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

    /**
     * Instantiate a new MorphTo relationship.
     *
     * Overrides the `newMorphTo` function on Eloquent's `HasRelationships` trait, allowing models that
     * use this trait to transform into models from the custom morph map.
     *
     * @template TRelatedModel of Model
     * @template TDeclaringModel of Model
     *
     * @param Builder<TRelatedModel> $query
     * @param TDeclaringModel $parent
     * @param string $foreignKey
     * @param string $ownerKey
     * @param string $type
     * @param string $relation
     * @return LoggerMorphTo<TRelatedModel, TDeclaringModel>
     */
    protected function newMorphTo(Builder $query, Model $parent, $foreignKey, $ownerKey, $type, $relation): MorphTo
    {
        return new LoggerMorphTo($query, $parent, $foreignKey, $ownerKey, $type, $relation);
    }
}
