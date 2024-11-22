<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Sourcetoad\Logger\Contracts\Trackable;
use Sourcetoad\Logger\Traits\Immutable;

/**
 * Class AuditModel
 * @package Sourcetoad\Logger\Models
 * @property int $id
 * @property int $activity_id
 * @property int $entity_type
 * @property int $entity_id
 * @property int|null $owner_type
 * @property int|null $owner_id
 * @property int|null $key_id
 * @property bool $processed
 * @property-read AuditKey $key
 * @property-read Model|null $owner
 * @property-read Trackable $entity
 */
class AuditChange extends BaseModel
{
    use Immutable;

    protected $fillable = [
        'activity_id',
        'entity_type',
        'entity_id',
        'key_id',
        'processed',
    ];

    public $timestamps = false;

    //--------------------------------------------------------------------------------------------------------------
    // Mutators
    //--------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------
    // Relations
    //--------------------------------------------------------------------------------------------------------------

    /** @return MorphTo<Model, self> */
    public function owner(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function key(): BelongsTo
    {
        return $this->belongsTo(AuditKey::class);
    }

    /** @return MorphTo<Trackable, self> */
    public function entity(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }
}
