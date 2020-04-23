<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Traits;

use Illuminate\Database\Eloquent\Model;
use Sourcetoad\Logger\Models\AuditChange;
use Sourcetoad\Logger\Models\AuditModel;

trait Immutable
{
    public static function bootImmutable()
    {
        static::updating(function (Model $model) {

            // We need to allow updates for the AuditModel (user_id/processed)
            if ($model instanceof AuditModel) {
                if ($model->isDirty(['user_id', 'processed']) &&
                    $model->isClean(['id', 'activity_id', 'entity_type', 'entity_id'])) {
                    return true;
                }
            }

            // We need to allow updates for the AuditChange (user_id/processed)
            if ($model instanceof AuditChange) {
                if ($model->isDirty(['user_id', 'processed']) &&
                    $model->isClean(['id', 'activity_id', 'entity_id', 'key_id'])) {
                    return true;
                }
            }

            return false;
        });

        static::deleting(function (Model $model) {
            return false;
        });
    }
}
