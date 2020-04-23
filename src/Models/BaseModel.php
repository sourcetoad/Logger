<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Models;

use Illuminate\Database\Eloquent\Model;
use Sourcetoad\Logger\Traits\HasRelationships;

class BaseModel extends Model
{
    use HasRelationships;
}
