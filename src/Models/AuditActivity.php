<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Models;

use App\User;
use Carbon\Carbon;
use Sourcetoad\Logger\Enums\ActivityType;
use Sourcetoad\Logger\Enums\HttpVerb;
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

    protected $dates = [
        'created_at'
    ];

    //--------------------------------------------------------------------------------------------------------------
    // Mutators
    //--------------------------------------------------------------------------------------------------------------

    protected function setIpAddressAttribute($value)
    {
        $this->attributes['ip_address'] = inet_pton($value);
    }

    protected function getIpAddressAttribute($value)
    {
        return strtoupper(inet_ntop($value));
    }

    public function setUpdatedAtAttribute($value)
    {
        // Nothing
    }

    public function getHumanVerbAttribute(): string
    {
        switch ($this->verb) {
            case HttpVerb::GET:
                return trans('logger::enums.verb_get');

            case HttpVerb::POST:
                return trans('logger::enums.verb_post');

            case HttpVerb::PATCH:
                return trans('logger::enums.verb_patch');

            case HttpVerb::PUT:
                return trans('logger::enums.verb_patch');

            case HttpVerb::DELETE:
                return trans('logger::enums.verb_delete');

            case HttpVerb::UNKNOWN:
            default:
                return trans('logger::enums.verb_unknown');
        }
    }

    public function getHumanActivityAttribute(): string
    {
        switch ($this->type) {
            case ActivityType::FAILED_LOGIN:
                return trans('logger::enums.activity_type_failed_login');

            case ActivityType::LOGOUT:
                return trans('logger::enums.activity_type_logout');

            case ActivityType::SUCCESSFUL_LOGIN:
                return trans('logger::enums.activity_type_logged_in');

            case ActivityType::LOCKED_OUT:
                return trans('logger::enums.activity_type_locked_out');

            case ActivityType::PASSWORD_CHANGE:
                return trans('logger::enums.activity_type_password_change');

            case ActivityType::GET_DATA:
                return trans('logger::enums.activity_type_get_data');

            case ActivityType::MODIFY_DATA:
                return trans('logger::enums.activity_type_modify_data');

            default:
                throw new \Exception('Unknown enum type: ' . $this->type);
        }
    }
    
    //--------------------------------------------------------------------------------------------------------------
    // Relations
    //--------------------------------------------------------------------------------------------------------------

    public function route()
    {
        return $this->belongsTo(AuditRoute::class);
    }

    public function key()
    {
        return $this->belongsTo(AuditKey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }
}