<?php

namespace App\Data;

use App\Http\Requests\UpdateProjectRequest;
use Spatie\LaravelData\Data;

class UpdateProjectData extends Data
{
    public function __construct(
        public ?string $code,
        public ?string $name,
        public ?string $kickoff_date,
        public ?int $duration_days,
        public ?string $description,
        public array $present = []
    ) {}

    public static function fromRequest(UpdateProjectRequest $request): self
    {
        $present = array_keys($request->validated());

        return new self(
            code: $request->input('code'),
            name: $request->input('name'),
            kickoff_date: $request->input('kickoff_date'),
            duration_days: $request->input('duration_days'),
            description: $request->input('description'),
            present: $present
        );
    }

    public function has(string $key): bool
    {
        return in_array($key, $this->present, true);
    }
}
