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
    public $timestamps = false;
}