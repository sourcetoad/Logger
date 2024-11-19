<?php

declare(strict_types = 1);

namespace Sourcetoad\Logger\Helpers;

use Illuminate\Database\Eloquent\Model;
use Sourcetoad\Logger\Contracts\Trackable;

class AuditResolver
{
    public static function findOwner(?Trackable $model): ?Model
    {
        if (empty($model)) {
            return null;
        }

        $owner = $model->trackableOwnerResolver();

        if (empty($owner)) {
            return null;
        }

        return $owner;
    }
}
