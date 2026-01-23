<?php

namespace App\Data\Project;

use Spatie\LaravelData\Data;

class DocumentData extends Data
{
    public function __construct(
        public string $title,
        public ?string $description = null,
    ) {}
}
