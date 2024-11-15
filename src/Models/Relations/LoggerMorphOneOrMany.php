<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Models\Relations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Support\Str;
use Sourcetoad\Logger\LoggerServiceProvider;
use RuntimeException;

/**
 * An abstract class that replaces Eloquent's `MorphOneToMany` class, acting as a basis
 * for polymorphic relations with Logger's models.
 *
 * @template TRelatedModel of Model
 * @template TDeclaringModel of Model
 * @template TResult
 *
 * @extends HasOneOrMany<TRelatedModel, TDeclaringModel, TResult>
 */
abstract class LoggerMorphOneOrMany extends HasOneOrMany
{
    /**
     * The foreign key type for the relationship.
     */
    protected string $morphType;

    /**
     * The class name of the parent model.
     */
    protected int $morphClass;

    /**
     * @param Builder<TRelatedModel> $query
     * @param TDeclaringModel $parent
     * @param string $type
     * @param string $id
     * @param string $localKey
     * @return void
     */
    public function __construct(Builder $query, Model $parent, $type, $id, $localKey)
    {
        $this->morphType = $type;

        $this->morphClass = $this->getLoggerMorphClass($parent);

        parent::__construct($query, $parent, $id, $localKey);
    }

    private function getLoggerMorphClass(Model $parent): int
    {
        $morphMap = LoggerServiceProvider::$morphs;

        if (! empty($morphMap) && in_array($parent::class, $morphMap)) {
            return array_search($parent::class, $morphMap, true);
        }

        throw new RuntimeException(sprintf('%s is not defined in the logger morph map.', $this->parent::class));
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints(): void
    {
        if (static::$constraints) {
            $this->getRelationQuery()->where($this->morphType, $this->morphClass);

            parent::addConstraints();
        }
    }

    /** @inheritDoc */
    public function addEagerConstraints(array $models): void
    {
        parent::addEagerConstraints($models);

        $this->getRelationQuery()->where($this->morphType, $this->morphClass);
    }

    /**
     * Create a new instance of the related model. Allow mass-assignment.
     *
     * @param array $attributes
     * @return TRelatedModel
     */
    public function forceCreate(array $attributes = []): Model
    {
        $attributes[$this->getForeignKeyName()] = $this->getParentKey();
        $attributes[$this->getMorphType()] = $this->morphClass;

        return $this->applyInverseRelationToModel($this->related->forceCreate($attributes));
    }

    /**
     * Set the foreign ID and type for creating a related model.
     *
     * @param TRelatedModel $model
     * @return void
     */
    protected function setForeignAttributesForCreate(Model $model): void
    {
        $model->{$this->getForeignKeyName()} = $this->getParentKey();

        $model->{$this->getMorphType()} = $this->morphClass;

        $this->applyInverseRelationToModel($model);
    }

    /**
     * Insert new records or update the existing ones.
     *
     * @param array $values
     * @param array|string $uniqueBy
     * @param array|null $update
     * @return int
     */
    public function upsert(array $values, $uniqueBy, array $update = null): int
    {
        if (! empty($values) && ! is_array(reset($values))) {
            $values = [$values];
        }

        foreach ($values as $key => $value) {
            $values[$key][$this->getMorphType()] = $this->getMorphClass();
        }

        return parent::upsert($values, $uniqueBy, $update);
    }

    /** @inheritDoc */
    public function getRelationExistenceQuery(Builder $query, Builder $parentQuery, $columns = ['*']): Builder
    {
        return parent::getRelationExistenceQuery($query, $parentQuery, $columns)
            ->where($query->qualifyColumn($this->getMorphType()), $this->morphClass);
    }

    /**
     * Get the foreign key "type" name.
     *
     * @return string
     */
    public function getQualifiedMorphType(): string
    {
        return $this->morphType;
    }

    /**
     * Get the plain morph type name without the table.
     *
     * @return string
     */
    public function getMorphType(): string
    {
        return last(explode('.', $this->morphType));
    }

    /**
     * Get the class name of the parent model.
     *
     * @return int
     */
    public function getMorphClass(): int
    {
        return $this->morphClass;
    }

    /**
     * Get the possible inverse relations for the parent model.
     *
     * @return array<non-empty-string>
     */
    protected function getPossibleInverseRelations(): array
    {
        return array_unique([
            Str::beforeLast($this->getMorphType(), '_type'),
            ...parent::getPossibleInverseRelations(),
        ]);
    }
}
