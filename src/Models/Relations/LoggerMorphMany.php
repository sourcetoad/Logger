<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Models\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TRelatedModel of Model
 * @template TDeclaringModel of Model
 *
 * @extends LoggerMorphOneOrMany<TRelatedModel, TDeclaringModel, Collection<int, TRelatedModel>>
 */
class LoggerMorphMany extends LoggerMorphOneOrMany
{
    /**
     * Convert the relationship to a "morph one" relationship.
     *
     * @return LoggerMorphOne<TRelatedModel, TDeclaringModel>
     */
    public function one()
    {
        return LoggerMorphOne::noConstraints(fn () => tap(
            new LoggerMorphOne(
                $this->getQuery(),
                $this->getParent(),
                $this->morphType,
                $this->foreignKey,
                $this->localKey
            ),
            function ($morphOne) {
                if ($inverse = $this->getInverseRelationship()) {
                    $morphOne->inverse($inverse);
                }
            }
        ));
    }

    /** {@inheritDoc} */
    public function getResults()
    {
        return ! is_null($this->getParentKey())
            ? $this->query->get()
            : $this->related->newCollection();
    }

    /** {@inheritDoc} */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->related->newCollection());
        }

        return $models;
    }

    /** {@inheritDoc} */
    public function match(array $models, Collection $results, $relation)
    {
        return $this->matchMany($models, $results, $relation);
    }

    /** {@inheritDoc} */
    public function forceCreate(array $attributes = [])
    {
        $attributes[$this->getMorphType()] = $this->morphClass;

        return parent::forceCreate($attributes);
    }
}
