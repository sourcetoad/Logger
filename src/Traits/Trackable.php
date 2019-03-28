<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Traits;

use Illuminate\Database\Eloquent\Model;
use Sourcetoad\Logger\Logger;

trait Trackable
{
    public static function bootTrackable()
    {
        static::retrieved(function (Model $model)
        {
            resolve(Logger::class)->logRetrievedModel($model);
        });

        // TODO - events for updating (to detect changed variables)
        static::updated(function (Model $model) {
            $dirtyAttributes = $model->getDirty();

            // TODO get keys and pass along to our function
        });
    }
}