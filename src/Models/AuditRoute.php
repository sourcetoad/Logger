<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AuditRoute
 * @package Sourcetoad\Logger\Models
 * @property int $id
 * @property string $route
 * @property string $route_hash
 */
class AuditRoute extends Model
{
    protected $fillable = [
        'route',
        'route_hash'
    ];

    public $timestamps = false;

    //--------------------------------------------------------------------------------------------------------------
    // Mutators
    //--------------------------------------------------------------------------------------------------------------

    protected function setRouteAttribute($value)
    {
        $this->attributes['route'] = strtolower(trim($value));
        $this->attributes['route_hash'] = md5($this->attributes['route']);
    }

    //--------------------------------------------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------------------------------------------

    public static function createOrFind(string $route): AuditRoute
    {
        $routeHash = md5(strtolower($route));

        /** @var AuditRoute $route */
        $route = AuditRoute::query()->firstOrCreate([
            'route_hash' => $routeHash,
        ], [
            'route' => $route,
        ]);

        return $route;
    }
}