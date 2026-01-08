<?php
namespace App\Data\Common;

use Spatie\LaravelData\Data;

class ApiSuccessResponseData extends Data
{
    public function __construct(
        public string $message,
        public mixed $data = null
    ) {}
}
