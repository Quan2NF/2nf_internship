<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];

    public function projectMembers(): BelongsToMany
    {
        return $this->belongsToMany(ProjectMember::class, 'project_member_roles')
            ->withTimestamps();
    }
}
