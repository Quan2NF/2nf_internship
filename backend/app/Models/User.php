<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'date_joined',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'tokens',       // hide API tokens (Sanctum)
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_joined'       => 'date',
            'status'            => UserStatus::class,
            'password'          => 'hashed', // auto-hash
        ];
    }

    // RELATIONSHIPS

    // Projects the user is assigned to (many-to-many)
    public function projects()
    {
        return $this->belongsToMany(Project::class)
                    ->withPivot('role_in_project', 'joined_at')
                    ->withTimestamps();
    }

    // Projects the user manages as PM (one-to-many)
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'pm_id');
    }

    // Issues assigned to the user (one-to-many)
    public function assignedIssues()
    {
        return $this->hasMany(Issue::class, 'assignee_id');
    }

    // Issues created by the user (one-to-many)
    public function createdIssues()
    {
        return $this->hasMany(Issue::class, 'creator_id');
    }
}
