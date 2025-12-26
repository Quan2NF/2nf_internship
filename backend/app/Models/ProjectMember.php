<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectMember extends Model
{
    use HasFactory;

    protected $table = 'project_members';

    protected $fillable = [
        'project_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'project_id' => 'integer',
            'user_id'    => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'project_member_roles',
            'project_member_id',
            'role_id'
        )->withTimestamps();
    }
}
