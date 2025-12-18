<?php

namespace App\Enums\Model\Issue;

enum IssueAuditAction : string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case STATUS_CHANGED = 'status_changed';
    case COMMENTED = 'commented';
    case DELETED = 'deleted';
}
