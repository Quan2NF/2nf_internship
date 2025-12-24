<?php

namespace App\Enums\Project;

enum ProjectStatus: int
{
    case PLANNED   = 1;
    case ACTIVE    = 2;
    case COMPLETED = 3;
    case ARCHIVED  = 4;
}
