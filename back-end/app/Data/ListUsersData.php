<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ListUsersData extends Data
{
    public function __construct(
        public ?string $search,
        public ?int $is_active,
        public ?string $position_code,
        public ?int $position_id,
        public ?string $join_date_from,
        public ?string $join_date_to,
        public int $per_page = 15,
        public ?string $sort = null,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            $input['search'] ?? null,
            isset($input['is_active']) ? (int) $input['is_active'] : null,
            $input['position_code'] ?? null,
            isset($input['position_id']) ? (int) $input['position_id'] : null,
            $input['join_date_from'] ?? null,
            $input['join_date_to'] ?? null,
            isset($input['per_page']) ? max(1, (int) $input['per_page']) : 15,
            $input['sort'] ?? null,
        );
    }
}
