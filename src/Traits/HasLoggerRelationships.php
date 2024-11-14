<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sourcetoad\Logger\Models\Relations\LoggerMorphMany;
use Sourcetoad\Logger\Models\Relations\LoggerMorphOne;

trait HasLoggerRelationships
{
    /**
     * Define a polymorphic one-to-many relationship with one of the Logger's models.
     *
     * Acts as an override on Eloquent's `morphMany` function to return a Logger-compatible relation.
     *
     * @template TRelatedModel of Model
     *
     * @param class-string<TRelatedModel> $related
     * @return LoggerMorphMany<TRelatedModel, $this>
     */
    public function loggerMorphMany(
        string $related,
        string $name,
        string $type = null,
        string $id = null,
        string $localKey = null,
    ): LoggerMorphMany {
        $instance = $this->newRelatedInstance($related);

        [$type, $id] = $this->getMorphs($name, $type, $id);

        $table = $instance->getTable();

        $localKey = $localKey ?: $this->getKeyName();

        return $this->newLoggerMorphMany($instance->newQuery(), $this, $table.'.'.$type, $table.'.'.$id, $localKey);
    }

    /**
     * Instantiate a new LoggerMorphMany relationship with one of Logger's models.
     *
     * Acts as an override on Eloquent's `newMorphMany` function to return a Logger-compatible relation.
     *
     * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
     * @template TDeclaringModel of \Illuminate\Database\Eloquent\Model
     *
     * @param \Illuminate\Database\Eloquent\Builder<TRelatedModel> $query
     * @param TDeclaringModel $parent
     * @return LoggerMorphMany<TRelatedModel, TDeclaringModel>
     */
    protected function newLoggerMorphMany(
        Builder $query,
        Model $parent,
        string $type,
        string $id,
        string $localKey,
    ): LoggerMorphMany {
        return new LoggerMorphMany($query, $parent, $type, $id, $localKey);
    }

    /**
     * Define a polymorphic one-to-one relationship with one of Logger's models.
     *
     * Acts as an override on Eloquent's `morphOne` function to return a Logger-compatible relation.
     *
     * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
     *
     * @param class-string<TRelatedModel> $related
     * @return LoggerMorphOne<TRelatedModel, $this>
     */
    public function loggerMorphOne(
        string $related,
        string $name,
        string $type = null,
        string $id = null,
        string $localKey = null,
    ): LoggerMorphOne {
        $instance = $this->newRelatedInstance($related);

        [$type, $id] = $this->getMorphs($name, $type, $id);

        $table = $instance->getTable();

        $localKey = $localKey ?: $this->getKeyName();

        return $this->newLoggerMorphOne($instance->newQuery(), $this, $table.'.'.$type, $table.'.'.$id, $localKey);
    }

    /**
     * Instantiate a new MorphOne relationship with one of Logger's models.
     *
     * Acts as an override on Eloquent's `newMorphOne` function to return a Logger-compatible relation.
     *
     * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
     * @template TDeclaringModel of \Illuminate\Database\Eloquent\Model
     *
     * @param \Illuminate\Database\Eloquent\Builder<TRelatedModel> $query
     * @param TDeclaringModel $parent
     * @return LoggerMorphOne<TRelatedModel, TDeclaringModel>
     */
    protected function newLoggerMorphOne(
        Builder $query,
        Model $parent,
        string $type,
        string $id,
        string $localKey,
    ): LoggerMorphOne {
        return new LoggerMorphOne($query, $parent, $type, $id, $localKey);
    }
}
