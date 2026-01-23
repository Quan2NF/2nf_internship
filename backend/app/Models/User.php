<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Position\PositionScope;
use App\Enums\User\UserGender;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

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
            'employee_code' => 'string',
            'name'          => 'string',
            'email'         => 'string',
            'password'      => 'hashed',
            'phone_number'  => 'string',
            'birthday'      => 'date',
            'gender'        => UserGender::class,
            'join_date'     => 'date',
            'resign_date'   => 'date',
            'avatar'        => 'string',
            'is_active'     => 'boolean',
        ];
    }

    // DEFAULTS
    protected $attributes = [
        'is_active' => true,
    ];

    // RELATIONSHIPS
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'user_positions')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_members')
                    ->withPivot('id') // needed to get project_member id
                    ->withTimestamps();
    }

    public function hasSystemPosition(string $code): bool
    {
        return $this->positions()
                    ->where('positions.code', $code)
                    ->where('positions.scope', PositionScope::System)
                    ->whereNull('positions.deleted_at')
                    ->where(function ($q) {
                        $q->whereNull('user_positions.end_date')
                        ->orWhere('user_positions.end_date', '>=', now());
                    })
                    ->exists();
    }
}
