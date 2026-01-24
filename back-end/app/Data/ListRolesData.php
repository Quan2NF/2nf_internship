<?php

namespace App\Data;

use App\Http\Requests\ListRolesRequest;
use Spatie\LaravelData\Data;

class ListRolesData extends Data
{
    public function __construct(
        public ?string $search,
        public int $per_page = 15,
        public ?string $sort = null,
    ) {}

    public static function fromRequest(ListRolesRequest $request): self
    {
        return new self(
            search: $request->input('search'),
            per_page: (int) ($request->input('per_page', 15)),
            sort: $request->input('sort')
        );
    }
}
