<?php

namespace App\Data\Response;

use Spatie\LaravelData\Data;
use App\Enums\ResponseCode;

class ApiResponseData extends Data
{
    public function __construct(
        public int $status,
        public ?ResponseCode $response_code,
        public mixed $data
    ) {}
}
