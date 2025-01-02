<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Models;

use Sourcetoad\Logger\Traits\Immutable;

/**
 * Class AuditRoute
 *
 * @property int $id
 * @property string $route
 * @property string $route_hash
 */
class AuditRoute extends BaseModel
{
    use Immutable;

    protected $fillable = [
        'route',
        'route_hash',
    ];

    public $timestamps = false;

    // --------------------------------------------------------------------------------------------------------------
    // Mutators
    // --------------------------------------------------------------------------------------------------------------

    protected function setRouteAttribute($value): void
    {
        $this->attributes['route'] = strtolower(trim($value));
        $this->attributes['route_hash'] = md5($this->attributes['route']);
    }

    // --------------------------------------------------------------------------------------------------------------
    // Functions
    // --------------------------------------------------------------------------------------------------------------

    public static function createOrFind(string $route): AuditRoute
    {
        $routeHash = md5(strtolower(trim($route)));

        /** @var AuditRoute $route */
        $route = AuditRoute::query()->lockForUpdate()->firstOrCreate([
            'route_hash' => $routeHash,
        ], [
            'route' => $route,
        ]);

        return $route;
    }
}
