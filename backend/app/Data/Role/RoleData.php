<?php

namespace App\Data\Role;

use App\Models\Role;
use Spatie\LaravelData\Data;

class RoleData extends Data
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
    ) {}

    /**
     * Create DTO from Role model.
     */
    public static function fromModel(Role $role): self
    {
        return new self(
            id: (int) $role->id,
            code: (string) $role->code,
            name: (string) $role->name,
        );
    }
}
