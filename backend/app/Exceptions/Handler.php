<?php

namespace App\Exceptions;

use Throwable;
use App\Data\Common\ApiErrorResponseData;
use App\Exceptions\Domain\BusinessException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {

            // Validation error
            if ($e instanceof ValidationException) {
                return response()->json(
                    new ApiErrorResponseData(
                        message: 'Validation failed',
                        errors: $e->errors()
                    ),
                    422
                );
            }

            // Business exception
            if ($e instanceof BusinessException) {
                return response()->json(
                    new ApiErrorResponseData(
                        message: $e->getMessage()
                    ),
                    $e->status()
                );
            }

            // Auth / Sanctum
            if ($e instanceof UnauthorizedHttpException) {
                return response()->json(
                    new ApiErrorResponseData(
                        message: 'Unauthenticated'
                    ),
                    401
                );
            }

            // Fallback
            return response()->json(
                new ApiErrorResponseData(
                    message: 'Internal server error'
                ),
                500
            );
        }

        return parent::render($request, $e);
    }
}
