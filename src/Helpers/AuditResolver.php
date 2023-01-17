<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Helpers;

use Illuminate\Database\Eloquent\Model;

class AuditResolver
{
    public static function findUserId(?Model $model): ?int
    {
        if (empty($model)) {
            return null;
        }

        $id = $model->trackableUserResolver();

        if (empty($id)) {
            return null;
        }

        return $id;
    }
}
