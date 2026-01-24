<?php

namespace App\Data;

use App\Http\Requests\UpdateRoleRequest;
use Spatie\LaravelData\Data;

class UpdateRoleData extends Data
{
    /**
     * @param array<int, string> $present
     */
    public function __construct(
        public ?string $code,
        public ?string $name,
        public array $present = [],
    ) {}

    public static function fromRequest(UpdateRoleRequest $request): self
    {
        $present = array_keys($request->validated());

        return new self(
            code: $request->input('code'),
            name: $request->input('name'),
            present: $present,
        );
    }

    public function has(string $key): bool
    {
        return in_array($key, $this->present, true);
    }
}
