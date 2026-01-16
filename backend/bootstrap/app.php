<?php

use App\Enums\ResponseCode;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;
use App\Data\Response\ApiResponseData;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $exceptions->render(function (AuthenticationException $e) { // thrown by auth middleware
            return ApiResponse::from(ResponseCode::EXPIRED_TOKEN);
        });

        $exceptions->render(function (AuthorizationException $e) { // thrown by authorize()
            return ApiResponse::from(ResponseCode::FORBIDDEN);
        });

        $exceptions->render(function (ValidationException $e) { // thrown by rules()
            return ApiResponse::from(ResponseCode::INVALID_TRANSMITTED_DATA);
        });

        $exceptions->render(function (ModelNotFoundException $e) { // thrown by findOrFail(), firstOrFail(), route model binding
            return ApiResponse::from(ResponseCode::DATA_NOT_FOUND);
        });

        $exceptions->render(function (Throwable $ex) {
            Log::error($ex);
            return ApiResponse::from(ResponseCode::SERVER_ERROR);
        });
    })->create();
