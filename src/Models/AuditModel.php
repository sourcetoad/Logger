<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Sourcetoad\Logger\Traits\Immutable;

/**
 * Class AuditModel
 * @package Sourcetoad\Logger\Models
 * @property int $id
 * @property int $activity_id
 * @property int $entity_type
 * @property int $entity_id
 * @property int|null $user_id
 * @property bool $processed
 * @property-read User|null $user
 * @property-read Model $entity
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
            'entity_id'   => $modelId,
        ]);

        return $model;
    }

    //--------------------------------------------------------------------------------------------------------------
    // Relations
    //--------------------------------------------------------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }
}
