<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\ApiException;
use App\Exceptions\AuthenticationException;
use App\Exceptions\AuthorizationException;
use Illuminate\Auth\AuthenticationException as LaravelAuthException;
use Illuminate\Auth\Access\AuthorizationException as LaravelAuthorizationException;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

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

        // Custom API Exception
        $exceptions->render(function (ApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'errors'  => $e->getErrors(),
            ], $e->getStatus());
        });

        // Authentication Exception
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?? 'Authentication failed',
                'error_code' => 'AUTH_FAILED',
                'errors' => $e->getErrors(),
            ], 401);
        });

        // Authorization Exception
        $exceptions->render(function (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?? 'Unauthorized action',
                'error_code' => 'FORBIDDEN',
                'errors' => $e->getErrors(),
            ], 403);
        });

        // Laravel Authentication Exception
        $exceptions->render(function (LaravelAuthException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated - Please login',
                'error_code' => 'UNAUTHENTICATED',
                'errors' => [],
            ], 401);
        });

        // Laravel Authorization Exception
        $exceptions->render(function (LaravelAuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'This action is unauthorized',
                'error_code' => 'FORBIDDEN',
                'errors' => [],
            ], 403);
        });

        // Validation Exception
        $exceptions->render(function (LaravelValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'error_code' => 'VALIDATION_ERROR',
                'errors'  => $e->errors(),
            ], 422);
        });

        // Not Found Exception
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
                'error_code' => 'NOT_FOUND',
                'errors'  => [],
            ], 404);
        });

        // Method Not Allowed Exception
        $exceptions->render(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed',
                'error_code' => 'METHOD_NOT_ALLOWED',
                'errors'  => [],
            ], 405);
        });

        // Conflict Exception
        $exceptions->render(function (ConflictHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?? 'Conflict with existing resource',
                'error_code' => 'CONFLICT',
                'errors' => [],
            ], 409);
        });

        // Generic Throwable
        $exceptions->render(function (Throwable $e) {
            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
            $statusCode = $statusCode < 100 || $statusCode >= 600 ? 500 : $statusCode;

            return response()->json([
                'success' => false,
                'message' => config('app.debug')
                    ? $e->getMessage()
                    : 'An error occurred while processing your request',
                'error_code' => 'SERVER_ERROR',
                'errors' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                ] : [],
            ], $statusCode);
        });
    })

    ->create();


