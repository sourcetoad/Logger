<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Sourcetoad\Logger\Logger;
use Sourcetoad\Logger\Traits\Immutable;

/**
 * Class AuditModel
 * @package Sourcetoad\Logger\Models
 * @property int $id
 * @property int $activity_id
 * @property int $entity_type
 * @property int $entity_id
 * @property int|null $user_id
 * @property int|null $key_id
 * @property bool $processed
 * @property-read AuditKey $key
 * @property-read User|null $user
 * @property-read Model $entity
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(Logger::$userModel);
    }

    public function key(): BelongsTo
    {
        return $this->belongsTo(AuditKey::class);
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
