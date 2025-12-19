<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case PLANNED   = 'planned';
    case ACTIVE    = 'active';
    case COMPLETED = 'completed';
    case ARCHIVED  = 'archived';
}
