<?php

namespace App\Enums\Task;

enum TaskRelationType: string
{
    case BLOCKS = 'blocks';
    case RELATES = 'relates';
    case DUPLICATES = 'duplicates';
}
