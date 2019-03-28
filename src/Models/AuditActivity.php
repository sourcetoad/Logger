<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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
 * @property Carbon $created_at
 * @property-read AuditRoute $route
 * @property-read AuditKey $key
 * @property-read User $user
 */
class AuditActivity extends Model
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