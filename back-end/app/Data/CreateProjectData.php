<?php

namespace App\Data;

use App\Http\Requests\CreateProjectRequest;
use Spatie\LaravelData\Data;

class CreateProjectData extends Data
{
    public function __construct(
        public string $code,
        public string $name,
        public string $kickoff_date,
        public int $duration_days,
        public ?string $description = null,
    ) {}

    public static function fromRequest(CreateProjectRequest $request): self
    {
        return new self(
            code: $request->string('code')->toString(),
            name: $request->string('name')->toString(),
            kickoff_date: $request->string('kickoff_date')->toString(),
            duration_days: $request->integer('duration_days'),
            description: $request->input('description'),
        );
    }
}
