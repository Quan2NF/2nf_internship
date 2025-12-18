<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Model\Issue\IssueAuditAction;

class AuditLog extends Model
{
    protected $fillable = [
        'issue_id',
        'user_id',
        'action',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'action' => IssueAuditAction::class,
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
