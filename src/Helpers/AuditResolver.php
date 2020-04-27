<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Helpers;

use Illuminate\Database\Eloquent\Model;

class AuditResolver
{
    /**
     * @param Model|null $model
     * @return int|null
     */
    public static function findUserId($model): ?int
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
