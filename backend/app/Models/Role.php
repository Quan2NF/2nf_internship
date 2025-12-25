<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    protected function casts(): array
    {
        return [
            'code' => 'string',
            'name' => 'string',
        ];
    }

    public function projectMembers()
    {
        return $this->belongsToMany(
            ProjectMember::class,
            'project_member_roles',
            'role_id',
            'project_member_id'
        )->withTimestamps();
    }
}
