<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'employee_code',
        'name',
        'email',
        'password',
        'phone_number',
        'birthday',
        'gender',
        'join_date',
        'resign_date',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date',
            'join_date' => 'date',
            'resign_date' => 'date',
            'gender' => 'integer',
            'is_active' => 'integer',
        ];
    }

    // Quan hệ: user - positions
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'user_positions')
            ->withPivot(['start_date', 'end_date'])
            ->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->positions()
            ->where(function ($q) {
                $q->where('code', 'ADMIN')
                  ->orWhere('is_admin', true);
            })
            ->exists();
    }

    public function hasPositionCode(string $code): bool
    {
        return $this->positions()->where('code', $code)->exists();
    }

    public function isPMO(): bool
    {
        return $this->hasPositionCode('PMO');
    }

    public function isPM(): bool
    {
        return $this->hasPositionCode('PM');
    }

    public function projectMemberships(): HasMany
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_members')
            ->withTimestamps();
    }

    public function isMemberOfProject(int $projectId): bool
    {
        return DB::table('project_members')
            ->where('project_id', $projectId)
            ->where('user_id', $this->id)
            ->exists();
    }

    public function isPmOfProject(int $projectId): bool
    {
        $pmRoleId = DB::table('roles')->where('code', 'PM')->orWhere('name', 'PM')->value('id');
        if (! $pmRoleId) return false;

        return DB::table('project_member_roles')
            ->join('project_members', 'project_member_roles.project_member_id', '=', 'project_members.id')
            ->where('project_members.project_id', $projectId)
            ->where('project_members.user_id', $this->id)
            ->where('project_member_roles.role_id', $pmRoleId)
            ->exists();
    }
}
