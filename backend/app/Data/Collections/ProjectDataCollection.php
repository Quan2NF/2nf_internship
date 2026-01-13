<?php

namespace App\Data\Collections;

use App\Data\ProjectData;
use Spatie\LaravelData\DataCollection;

class ProjectDataCollection extends DataCollection
{
    public function __construct(array $items)
    {
        parent::__construct(ProjectData::class, $items);
    }
}
