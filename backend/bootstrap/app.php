<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\ApiException;
use Illuminate\Auth\AuthenticationException as LaravelAuthException;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {

        $middleware->api([
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (ApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors'  => $e->getErrors(),
            ], $e->getStatus());
        });

        $exceptions->render(function (LaravelValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        });

        $exceptions->render(function (LaravelAuthException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'errors'  => [],
            ], 401);
        });

        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Route not found',
                'errors'  => [],
            ], 404);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed',
                'errors'  => [],
            ], 405);
        });

        $exceptions->render(function (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => config('app.debug')
                    ? $e->getMessage()
                    : 'Internal Server Error',
                'errors' => [],
            ], 500);
        });
    })

    ->create();

