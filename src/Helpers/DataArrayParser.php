<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DataArrayParser
{
    public static function dedupe(array $data): array
    {
        return collect(Arr::dot($data))
            ->keys()
            ->map(function (string $key) {
                $removableIndexPairs = [
                    'data',
                    'entries',
                ];

                $purgedKey = preg_replace([
                    '/[0-9]+/',
                    '/(\.)+/',
                ], [
                    '',
                    '.',
                ], Str::remove($removableIndexPairs, $key));

                return trim($purgedKey, '.');
            })
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }
}
