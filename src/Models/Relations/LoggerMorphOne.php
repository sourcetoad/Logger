<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Models\Relations;

use Illuminate\Contracts\Database\Eloquent\SupportsPartialRelations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\CanBeOneOfMany;
use Illuminate\Database\Eloquent\Relations\Concerns\ComparesRelatedModels;
use Illuminate\Database\Eloquent\Relations\Concerns\SupportsDefaultModels;
use Illuminate\Database\Query\JoinClause;

/**
 * @template TRelatedModel of Model
 * @template TDeclaringModel of Model
 *
 * @extends LoggerMorphOneOrMany<TRelatedModel, TDeclaringModel, ?TRelatedModel>
 */
class LoggerMorphOne extends LoggerMorphOneOrMany implements SupportsPartialRelations
{
    use CanBeOneOfMany;
    use ComparesRelatedModels;
    use SupportsDefaultModels;

    /** @inheritDoc */
    public function getResults(): ?Model
    {
        if (is_null($this->getParentKey())) {
            return $this->getDefaultFor($this->parent);
        }

        return $this->query->first() ?: $this->getDefaultFor($this->parent);
    }

    /** @inheritDoc */
    public function initRelation(array $models, $relation): array
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->getDefaultFor($model));
        }

        return $models;
    }

    /** @inheritDoc */
    public function match(array $models, Collection $results, $relation): array
    {
        return $this->matchOne($models, $results, $relation);
    }

    /** @inheritDoc */
    public function getRelationExistenceQuery(Builder $query, Builder $parentQuery, $columns = ['*']): Builder
    {
        if ($this->isOneOfMany()) {
            $this->mergeOneOfManyJoinsTo($query);
        }

        return parent::getRelationExistenceQuery($query, $parentQuery, $columns);
    }

    /**
     * Add constraints for inner join subselect for one of many relationships.
     *
     * @param Builder<TRelatedModel> $query
     * @param string|null $column
     * @param string|null $aggregate
     * @return void
     */
    public function addOneOfManySubQueryConstraints(Builder $query, $column = null, $aggregate = null): void
    {
        $query->addSelect($this->foreignKey, $this->morphType);
    }

    /**
     * Get the columns that should be selected by the one of many subquery.
     *
     * @return array|string
     */
    public function getOneOfManySubQuerySelectColumns(): array|string
    {
        return [$this->foreignKey, $this->morphType];
    }

    /**
     * Add join query constraints for one of many relationships.
     *
     * @param JoinClause $join
     * @return void
     */
    public function addOneOfManyJoinSubQueryConstraints(JoinClause $join): void
    {
        $join
            ->on($this->qualifySubSelectColumn($this->morphType), '=', $this->qualifyRelatedColumn($this->morphType))
            ->on($this->qualifySubSelectColumn($this->foreignKey), '=', $this->qualifyRelatedColumn($this->foreignKey));
    }

    /**
     * Make a new related instance for the given model.
     *
     * @param TDeclaringModel $parent
     * @return TRelatedModel
     */
    public function newRelatedInstanceFor(Model $parent): Model
    {
        return tap($this->related->newInstance(), function ($instance) use ($parent) {
            $instance->setAttribute($this->getForeignKeyName(), $parent->{$this->localKey})
                ->setAttribute($this->getMorphType(), $this->morphClass);

            $this->applyInverseRelationToModel($instance, $parent);
        });
    }

    /**
     * Get the value of the model's foreign key.
     *
     * @param TRelatedModel $model
     * @return int|string
     */
    protected function getRelatedKeyFrom(Model $model): int|string
    {
        return $model->getAttribute($this->getForeignKeyName());
    }
}
