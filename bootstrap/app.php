<?php

use App\Enums\HttpStatus;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Illuminate\Foundation\Configuration\Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Illuminate\Http\Request $request, Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });

        $exceptions->renderable(function (Throwable $e, Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                $status = 500;

                if ($e instanceof ModelNotFoundException) {
                    $status = HttpStatus::NOT_FOUND->value;
                }

                if ($e instanceof InternalServerErrorException) {
                    $status = HttpStatus::INTERNAL_SERVER_ERROR->value;
                }

                return response()->json([
                    'error' => $e->getMessage(),
                ], $status);
            }
        });
    })->create();
