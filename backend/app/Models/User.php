<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\User\UserGender;
use App\Enums\User\UserStatus;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
    ];

    protected function casts(): array
    {
        return [
            'password'    => 'hashed',
            'birthday'    => 'date',
            'gender'      => UserGender::class,
            'join_date'   => 'date',
            'resign_date' => 'date',
            'is_active'   => 'boolean',
        ];
    }

    // DEFAULTS
    protected $attributes = [
        'is_active' => true,
    ];

    // RELATIONSHIPS
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'user_positions')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_members')
                    ->withPivot('id') // needed to get project_member id
                    ->withTimestamps();
    }
}
