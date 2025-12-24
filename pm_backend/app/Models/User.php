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

    protected $fillable = [
        'employee_code',   // Mã nhân viên
        'name',
        'email',
        'password',
        'phone_number',
        'birthday',
        'gender',          // 1: Male, 2: Female, 3: Other
        'join_date',
        'resign_date',
        'avatar',
        'is_active',       // 1: Active, 2: Inactive
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'birthday' => 'date',
        'join_date' => 'date',
        'resign_date' => 'date',
        'is_active' => 'integer',
    ];

    // Nếu muốn quan hệ với Role
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }
    public function hasRole(string|array $roles): bool {
        $roles = (array) $roles;
        return $this->roles->pluck('code')->intersect($roles)->isNotEmpty();
    }
}
