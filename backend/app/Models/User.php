<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    const GENDER_OTHER = 3;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_PM = 'pm';
    const ROLE_USER = 'user';

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
        'role',
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
            'is_active' => 'integer',
            'gender' => 'integer',
        ];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', self::STATUS_INACTIVE);
    }

    public function scopeWithoutTrashed($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeOnlyTrashed($query)
    {
        return $query->whereNotNull('deleted_at');
    }

    // Accessors
    public function getIsActiveStatusAttribute(): string
    {
        return $this->is_active === self::STATUS_ACTIVE ? 'Active' : 'Inactive';
    }

    public function getGenderLabelAttribute(): ?string
    {
        return match($this->gender) {
            self::GENDER_MALE => 'Male',
            self::GENDER_FEMALE => 'Female',
            self::GENDER_OTHER => 'Other',
            default => null,
        };
    }

    // Relationships
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id')
                    ->withTimestamps();
    }

    // Role & Permission Methods
    public function hasRole($roles): bool
    {
        if (is_string($roles)) {
            return $this->role === $roles;
        }

        return in_array($this->role, (array) $roles);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function can($ability, $resource = null): bool
    {
        // Admins can do everything
        if ($this->isAdmin()) {
            return true;
        }

        // Handle specific abilities
        return match ($ability) {
            'manage users' => $this->isAdmin() || $this->isManager(),
            'manage roles' => $this->isAdmin(),
            'manage projects' => $this->isAdmin() || $this->isManager() || $this->role === self::ROLE_PM,
            'manage settings' => $this->isAdmin(),
            default => parent::can($ability, $resource),
        };
    }
}
