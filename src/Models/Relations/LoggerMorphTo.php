<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Models\Relations;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Sourcetoad\Logger\Models\BaseModel;

/**
 * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
 * @template TDeclaringModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends \Illuminate\Database\Eloquent\Relations\BelongsTo<TRelatedModel, TDeclaringModel>
 */
class LoggerMorphTo extends MorphTo
{
    /**
     * Create a new model instance by type.
     *
     * @param  string  $type
     * @return TRelatedModel
     */
    public function createModelByType($type)
    {
        $class = BaseModel::getActualClassNameForMorph($type);

        return tap(new $class, function ($instance) {
            if (! $instance->getConnectionName()) {
                $instance->setConnection($this->getConnection()->getName());
            }
        });
    }
}
