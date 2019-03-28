<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Sourcetoad\Logger\Traits\Immutable;

/**
 * Class AuditKey
 * @package Sourcetoad\Logger\Models
 * @property int $id
 * @property string $data
 * @property string $hash
 */
class AuditKey extends Model
{
    use Immutable;

    protected $fillable = [
        'data',
        'hash',
    ];

    public $timestamps = false;

    //--------------------------------------------------------------------------------------------------------------
    // Mutators
    //--------------------------------------------------------------------------------------------------------------

    protected function setRouteAttribute($value)
    {
        if (is_array($value)) {
            $flattenedKeys = array_keys(Arr::dot($value));
            sort($flattenedKeys);
            $value = json_encode($flattenedKeys);
        }

        $this->attributes['data'] = $value;
        $this->attributes['hash'] = md5($value);
    }

    //--------------------------------------------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------------------------------------------

    public static function createOrFind(array $keys): AuditKey
    {
        $flattenedKeys = array_keys(Arr::dot($keys));
        sort($flattenedKeys);

        $jsonBlob = json_encode($flattenedKeys);
        $jsonHash = md5($jsonBlob);

        /** @var AuditKey $key */
        $key = AuditKey::query()
            ->firstOrCreate([
                'hash' => $jsonHash
            ], [
                'data' => $jsonBlob
            ]);

        return $key;
    }
}