<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Traits;

use Illuminate\Database\Eloquent\Model;
use Sourcetoad\Logger\Logger;

trait Trackable
{
    public static function bootTrackable(): void
    {
        static::retrieved(function (Model $model) {
            resolve(Logger::class)->logRetrievedModel($model);
        });

        static::updated(function (Model $model) {
            $dirtyAttributes = $model->getDirty();

            // don't care about dates
            unset($dirtyAttributes['updated_at']);
            unset($dirtyAttributes['created_at']);

            $keys = array_keys($dirtyAttributes);

            resolve(Logger::class)->logChangedModel($model, $keys);
        });
    }

    public function trackableUserResolver(): ?int
    {
        throw new \BadMethodCallException('This method (trackableUserResolver) needs ' .
            'to be implemented in the implemented class: ' . static::class);
    }
}
