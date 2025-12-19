<?php

namespace App\Enums;

enum IssueStatus: string
{
    case OPEN        = 'open';
    case TODO        = 'todo';
    case IN_PROGRESS = 'in_progress';
    case REVIEWING   = 'reviewing';
    case RE_OPEN     = 're_open';
    case DONE        = 'done';
    case CLOSE       = 'close';
}
