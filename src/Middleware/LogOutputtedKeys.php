<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Sourcetoad\Logger\Enums\ActivityType;
use Sourcetoad\Logger\Logger;

class LogOutputtedKeys
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * @param Request $request
     * @param $response
     */
    public function terminate(Request $request, $response)
    {
        $leadingStatusCodeNumber = substr((string) $response->getStatusCode(), 0, 1);

        // If we got a 400 or 500. Toss them. We don't need to log errors, as they don't leak information
        // (when properly setup)
        if (in_array($leadingStatusCodeNumber, [4, 5])) {
            return;
        }

        $data = [];
        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
        } else {
            throw new \InvalidArgumentException("Logger could not decode class: " . get_class($response));
        }

        if ($request->method() === 'GET') {
            resolve(Logger::class)->logActivity(ActivityType::GET_DATA, $data);
        } else {
            resolve(Logger::class)->logActivity(ActivityType::MODIFY_DATA, $data);
        }
    }
}
