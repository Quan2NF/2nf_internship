<?php

namespace App\Data;

use App\Http\Requests\ListProjectsRequest;
use Spatie\LaravelData\Data;

class ListProjectsData extends Data
{
    public function __construct(
        public ?string $search,
        public int $per_page = 15,
        public ?string $status = null,
        public ?string $sort = null,
    ) {}

    public static function fromRequest(ListProjectsRequest $request): self
    {
        return new self(
            search: $request->input('search'),
            per_page: (int) ($request->input('per_page', 15)),
            status: $request->input('status'),
            sort: $request->input('sort')
        );
    }
}
