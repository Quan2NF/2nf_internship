<?php

namespace App\Data\Project;

use Spatie\LaravelData\Data;

class ProjectSettingsData extends Data
{
    
    public function __construct(
        public ?string $wiki_content = null,
        public ?DocumentData $document = null,
    ) {}
}
