<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Sourcetoad\Logger\Traits\Immutable;

/**
 * Class AuditModel
 * @package Sourcetoad\Logger\Models
 * @property int $id
 * @property int $activity_id
 * @property int $entity_type
 * @property int $entity_id
 * @property int|null $user_id
 * @property string $fields
 * @property-read User|null $user
 */
class AuditChange extends Model
{
    use Immutable;

    protected $fillable = [
        'activity_id',
        'entity_type',
        'entity_id',
        'fields',
    ];

    public $timestamps = false;

    //--------------------------------------------------------------------------------------------------------------
    // Mutators
    //--------------------------------------------------------------------------------------------------------------

    protected function setFieldsAttribute($value)
    {
        if (is_array($value)) {
            $flattenedKeys = array_keys(Arr::dot($value));
            sort($flattenedKeys);
            $value = json_encode($flattenedKeys);
        }

        $this->attributes['fields'] = $value;
    }

    //--------------------------------------------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------
    // Relations
    //--------------------------------------------------------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}