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
}
