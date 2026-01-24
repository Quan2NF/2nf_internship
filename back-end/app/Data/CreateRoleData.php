<?php

namespace App\Data;

use App\Http\Requests\CreateRoleRequest;
use Spatie\LaravelData\Data;

class CreateRoleData extends Data
{
    public function __construct(
        public string $code,
        public string $name,
    ) {}

    public static function fromRequest(CreateRoleRequest $request): self
    {
        return new self(
            code: $request->string('code')->toString(),
            name: $request->string('name')->toString(),
        );
    }
}
