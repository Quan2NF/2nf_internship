<?php

namespace App\Events\Issue;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IssueCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Issue $issue,
        public User $creator
    ) {}
}
