<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMemberRole extends Model
{
    protected $table = 'project_member_roles';

    protected $fillable = [
        'project_member_id',
        'role_id',
    ];

    public function projectMember(): BelongsTo
    {
        return $this->belongsTo(ProjectMember::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
