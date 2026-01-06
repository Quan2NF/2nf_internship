<?php
namespace App\Data\Common;

use Spatie\LaravelData\Data;

class ApiErrorResponseData extends Data
{
    public function __construct(
        public string $message,
        public ?array $errors = null,
        public ?string $code = null
    ) {}
}
