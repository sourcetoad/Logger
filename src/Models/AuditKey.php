<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Models;

use Illuminate\Support\Arr;
use Sourcetoad\Logger\Helpers\DataArrayParser;
use Sourcetoad\Logger\Traits\Immutable;

/**
 * Class AuditKey
 *
 * @property int $id
 * @property string $data
 * @property string $hash
 */
class AuditKey extends BaseModel
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

    protected function setRouteAttribute($value): void
    {
        $value = Arr::wrap($value);
        $flattenedKeys = DataArrayParser::dedupe($value);
        $jsonBlob = json_encode($flattenedKeys);

        $this->attributes['data'] = $jsonBlob;
        $this->attributes['hash'] = md5($jsonBlob);
    }

    //--------------------------------------------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------------------------------------------

    public static function createOrFind(array $keys): AuditKey
    {
        $flattenedKeys = DataArrayParser::dedupe($keys);

        $jsonBlob = json_encode($flattenedKeys);
        $jsonHash = md5($jsonBlob);

        /** @var AuditKey $key */
        $key = AuditKey::query()
            ->lockForUpdate()
            ->firstOrCreate([
                'hash' => $jsonHash,
            ], [
                'data' => $jsonBlob,
            ]);

        return $key;
    }
}
