<?php declare(strict_types=1);

namespace App\Middlewares;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\{Context, Log};
use Illuminate\Support\Str;

final class ApiRequestLogger
{
    public function handle(Request $request, \Closure $next): Response
    {
        Context::add(key: 'request_id', value: Str::uuid()->toString());
        Context::add(key: 'timestamp', value: now()->toIso8601String());

        Context::add(key: 'path', value: $request->path());
        Context::add(key: 'method', value: $request->method());

        if ($request->user()) {
            Context::add(key: 'user_id', value: $request->user()->id);
        }

        $startTime = microtime(as_float: true);

        $responseTime = round(
            num: (microtime(as_float: true) - $startTime) * 1000,
            precision: 2
        );
        
        $response = $next($request);

        Context::add(key: 'response_time', value: $responseTime);
        Context::add(key: 'status_code', value: $response->getStatusCode());

        Log::info(message: 'API Request Processed');

        return $response;
    }
}
