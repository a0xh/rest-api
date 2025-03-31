<?php declare(strict_types=1);

use App\Shared\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\{Request, Response};
use Tymon\JWTAuth\Exceptions\JWTException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (JWTException $e, Request $request) {
            return response()->json(['error' => $e->getMessage()], 401);
        });
        
        $exceptions->level(\PDOException::class, \Psr\Log\LogLevel::CRITICAL);

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, Request $request) {
            return response()->json(['error' => $e->errors()], 422);
        });
    })->create();
