<?php

declare(strict_types=1);

namespace Sourcetoad\Logger\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Sourcetoad\Logger\Enums\ActivityType;
use Sourcetoad\Logger\Logger;

class LogOutputtedKeys
{
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }

    public function terminate(Request $request, $response): void
    {
        $leadingStatusCodeNumber = substr((string) $response->getStatusCode(), 0, 1);

        // If we got a 400 or 500. Toss them. We don't need to log errors, as they don't leak information
        // (when properly setup)
        if (in_array($leadingStatusCodeNumber, [4, 5])) {
            return;
        }

        if ($response instanceof JsonResponse) {
            $data = Arr::wrap($response->getData(true));
        } elseif ($response instanceof Response) {
            $data = [];
        } elseif ($response instanceof RedirectResponse) {
            $data = [];
        } else {
            $data = [];
            Log::warning('Could not decode class to extract data keys: '.get_class($response));
        }

        if ($request->method() === 'GET') {
            resolve(Logger::class)->logActivity(ActivityType::GET_DATA, $data);
        } else {
            resolve(Logger::class)->logActivity(ActivityType::MODIFY_DATA, $data);
        }
    }
}
