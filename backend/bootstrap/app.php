<?php

use App\Enums\ResponseCode;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;
use App\Data\Response\ApiResponseData;
use Illuminate\Foundation\Application;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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
        $exceptions->render(function (ValidationException $e) {
            return ApiResponse::from(ResponseCode::INVALID_TRANSMITTED_DATA);
        });


        $exceptions->render(function (Throwable $ex) {
            return ApiResponse::from(ResponseCode::SERVER_ERROR);
        });
    })->create();
