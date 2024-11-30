<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Sourcetoad\Logger\Contracts\Trackable;
use Sourcetoad\Logger\Traits\Immutable;

/**
 * Class AuditModel
 *
 * @property int $id
 * @property int $activity_id
 * @property int $entity_type
 * @property int $entity_id
 * @property int|null $owner_type
 * @property int|null $owner_id
 * @property bool $processed
 * @property-read Model|null $owner
 * @property-read Trackable $entity
 */
class AuditModel extends BaseModel
{
    use Immutable;

    protected $fillable = [
        'activity_id',
        'entity_type',
        'entity_id',
        'processed',
    ];

    public $timestamps = false;

    //--------------------------------------------------------------------------------------------------------------
    // Mutators
    //--------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------------------------------------------

    public static function createOrFind(AuditActivity $activity, int $modelType, int $modelId): AuditModel
    {
        /** @var AuditModel $model */
        $model = AuditModel::query()->lockForUpdate()->create([
            'activity_id' => $activity->id,
            'entity_type' => $modelType,
            'entity_id' => $modelId,
        ]);

        return $model;
    }

    //--------------------------------------------------------------------------------------------------------------
    // Relations
    //--------------------------------------------------------------------------------------------------------------

    /** @return MorphTo<Model, self> */
    public function owner(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    /** @return MorphTo<Trackable, self> */
    public function entity(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }
}
