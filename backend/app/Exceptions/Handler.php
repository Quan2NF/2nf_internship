<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use App\Data\Common\ApiErrorResponseData;
use App\Exceptions\Domain\BusinessException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {

            // 422 - Validation
            if ($e instanceof ValidationException) {
                return response()->json(
                    new ApiErrorResponseData(
                        message: 'DATA_INVALID',
                        errors: $e->errors()
                    ),
                    422
                );
            }

            // Business Exception (400, 403, ...)
            if ($e instanceof BusinessException) {
                return response()->json(
                    new ApiErrorResponseData(
                        message: $e->getMessage()
                    ),
                    $e->getStatusCode() // ✅ ĐÚNG
                );
            }

            // 401 - Auth
            if ($e instanceof UnauthorizedHttpException) {
                return response()->json(
                    new ApiErrorResponseData(
                        message: 'UNAUTHENTICATED'
                    ),
                    401
                );
            }

            // 500 - Fallback
            return response()->json(
                new ApiErrorResponseData(
                    message: 'INTERNAL_SERVER_ERROR'
                ),
                500
            );
        }

        return parent::render($request, $e);
    }
}
