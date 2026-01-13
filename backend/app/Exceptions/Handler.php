<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, Request $request) {
            return $this->handleException($e, $request);
        });
    }

    /**
     * Handle custom exceptions and render API responses
     */
    protected function handleException(Throwable $e, Request $request): ?JsonResponse
    {
        // Handle API exceptions
        if ($e instanceof ApiException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => $this->getErrorCode($e),
                'errors' => $e->getErrors(),
            ], $e->getStatus());
        }

        // Handle Laravel validation exceptions
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'error_code' => 'VALIDATION_ERROR',
                'errors' => $e->errors(),
            ], 422);
        }

        // Handle model not found exceptions
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
                'error_code' => 'NOT_FOUND',
                'errors' => [],
            ], 404);
        }

        // Handle authorization exceptions
        if ($e instanceof \Illuminate\Auth\AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action',
                'error_code' => 'UNAUTHORIZED',
                'errors' => [],
            ], 403);
        }

        // Handle authentication exceptions
        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
                'error_code' => 'UNAUTHENTICATED',
                'errors' => [],
            ], 401);
        }

        // Handle HTTP exceptions
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'An error occurred',
                'error_code' => 'HTTP_ERROR',
                'errors' => [],
            ], $e->getStatusCode());
        }

        // Handle other exceptions in production
        if (config('app.debug') === false && !($request->expectsJson())) {
            return null;
        }

        // Return JSON response for API requests with other exceptions
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                'error_code' => 'SERVER_ERROR',
                'errors' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : [],
            ], 500);
        }

        return null;
    }

    /**
     * Get error code based on exception type
     */
    protected function getErrorCode(ApiException $e): string
    {
        return match (get_class($e)) {
            AuthenticationException::class => 'AUTHENTICATION_ERROR',
            AuthorizationException::class => 'AUTHORIZATION_ERROR',
            NotFoundException::class => 'NOT_FOUND',
            ValidationException::class => 'VALIDATION_ERROR',
            default => 'API_ERROR',
        };
    }
}
