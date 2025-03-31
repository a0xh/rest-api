<?php declare(strict_types=1);

use App\Shared\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\{Request, Response};
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Responses\MessageResponse;
use Illuminate\Http\Middleware\HandleCors;
use App\Middlewares\ApiRequestLogger;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [HandleCors::class]);
        $middleware->api(append: [ApiRequestLogger::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(
            using: function (JWTException $e, Request $request) {
                return new MessageResponse(
                    message: $e->getMessage(),
                    status: Response::HTTP_UNAUTHORIZED
                );
            }
        );
        
        $exceptions->level(
            type: \PDOException::class,
            level: \Psr\Log\LogLevel::CRITICAL
        );
    })->create();
