<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function projectMembers(): BelongsToMany
    {
        return $this->belongsToMany(
            ProjectMember::class,
            'project_member_roles',
            'role_id',
            'project_member_id'
        )->withTimestamps();
    }
}
