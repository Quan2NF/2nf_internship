<?php

namespace App\Exceptions;

use App\Data\Common\ApiErrorResponseData;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // ===== API JSON RESPONSE =====
        if ($request->expectsJson()) {

            // 1. Validation error → 422
            if ($e instanceof ValidationException) {
                return response()->json(
                    new ApiErrorResponseData(
                        message: 'DATA_INVALID',
                        errors: $e->errors()
                    ),
                    422
                );
            }

            // 2. Business / Domain / Auth / Forbidden / NotFound
            if ($e instanceof HttpExceptionInterface) {
                return response()->json(
                    new ApiErrorResponseData(
                        message: $e->getMessage()
                    ),
                    $e->getStatusCode()
                );
            }

            // 3. Fallback system error
            return response()->json(
                new ApiErrorResponseData(
                    message: 'INTERNAL_SERVER_ERROR'
                ),
                500
            );
        }

        // ===== WEB REQUEST =====
        return parent::render($request, $e);
    }
}
