<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Sourcetoad\Logger\Models\BaseModel;

/**
 * @template TRelatedModel of Model
 * @template TDeclaringModel of Model
 *
 * @extends BelongsTo<TRelatedModel, TDeclaringModel>
 */
class LoggerMorphTo extends MorphTo
{
    /**
     * Overrides `createModelByType` on the parent class, allowing `morphTo` calls on
     * models extending Logger's `BaseModel` to map polymorphic relationships using the
     * custom morph map.
     *
     * @param string $type
     * @return TRelatedModel
     */
    public function createModelByType($type): Model
    {
        $class = BaseModel::getActualClassNameForMorph($type);

        return tap(new $class, function ($instance) {
            if (! $instance->getConnectionName()) {
                $instance->setConnection($this->getConnection()->getName());
            }
        });
    }
}
