<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Models;

use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Sourcetoad\Logger\Enums\ActivityType;
use Sourcetoad\Logger\Enums\HttpVerb;
use Sourcetoad\Logger\Logger;
use Sourcetoad\Logger\Traits\Immutable;

/**
 * Class AuditActivity
 * @package Sourcetoad\Logger\Models
 * @property int $id
 * @property int $route_id
 * @property int $key_id
 * @property int $user_id
 * @property int|null $entity_type
 * @property int|null $entity_id
 * @property int $type
 * @property string $ip_address
 * @property int $verb
 * @property Carbon $created_at
 * @property-read AuditRoute $route
 * @property-read AuditKey $key
 * @property-read User $user
 * @property-read string $human_verb
 * @property-read string $human_activity
 */
class AuditActivity extends BaseModel
{
    use Immutable;

    protected $fillable = [
        'route_id',
        'key_id',
        'user_id',
        'entity_type',
        'entity_id',
        'type',
        'verb',
        'ip_address'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    //--------------------------------------------------------------------------------------------------------------
    // Mutators
    //--------------------------------------------------------------------------------------------------------------

    protected function setIpAddressAttribute(?string $value): void
    {
        $this->attributes['ip_address'] = inet_pton((string)$value);
    }

    protected function getIpAddressAttribute($value): string
    {
        return strtoupper(inet_ntop($value));
    }

    public function setUpdatedAtAttribute($value)
    {
        // Nothing
    }

    public function getHumanVerbAttribute(): string
    {
        return match ($this->verb) {
            HttpVerb::GET => trans('logger::enums.verb_get'),
            HttpVerb::POST => trans('logger::enums.verb_post'),
            HttpVerb::PATCH, HttpVerb::PUT => trans('logger::enums.verb_patch'),
            HttpVerb::DELETE => trans('logger::enums.verb_delete'),
            default => trans('logger::enums.verb_unknown'),
        };
    }

    public function getHumanActivityAttribute(): string
    {
        return match ($this->type) {
            ActivityType::FAILED_LOGIN => trans('logger::enums.activity_type_failed_login'),
            ActivityType::LOGOUT => trans('logger::enums.activity_type_logout'),
            ActivityType::SUCCESSFUL_LOGIN => trans('logger::enums.activity_type_logged_in'),
            ActivityType::LOCKED_OUT => trans('logger::enums.activity_type_locked_out'),
            ActivityType::PASSWORD_CHANGE => trans('logger::enums.activity_type_password_change'),
            ActivityType::GET_DATA => trans('logger::enums.activity_type_get_data'),
            ActivityType::MODIFY_DATA => trans('logger::enums.activity_type_modify_data'),
            default => throw new Exception('Unknown enum type: ' . $this->type),
        };
    }
    
    //--------------------------------------------------------------------------------------------------------------
    // Relations
    //--------------------------------------------------------------------------------------------------------------

    public function route(): BelongsTo
    {
        return $this->belongsTo(AuditRoute::class);
    }

    public function key(): BelongsTo
    {
        return $this->belongsTo(AuditKey::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Logger::$userModel);
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
