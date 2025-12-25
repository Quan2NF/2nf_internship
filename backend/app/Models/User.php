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
}