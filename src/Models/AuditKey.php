<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AuditKey
 * @package Sourcetoad\Logger\Models
 * @property int $id
 * @property string $data
 * @property string $hash
 */
class AuditKey extends Model
{
    public $timestamps = false;
}