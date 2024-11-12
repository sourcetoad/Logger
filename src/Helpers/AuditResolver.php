<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Helpers;

use Illuminate\Database\Eloquent\Model;

class AuditResolver
{
    public static function findOwner(?Model $model): ?Model
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
