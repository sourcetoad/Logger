<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Trackable
{
    public function getOwnerRelationshipName(): ?string;

    public function trackableOwnerResolver(): ?Model;
}
