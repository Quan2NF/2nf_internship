<?php

namespace App\Http\Controllers\Concerns;

trait ApiResponse
{
    protected function success(
        string $message,
        mixed $data = null,
        int $status = 200
    ) {
        return response()->json([
            'message' => $message,
            'data'    => $data,
        ], $status);
    }
}
