<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'position',
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
            'status'            => 'string',
            'password'          => 'hashed', // auto-hash
        ];
    }

    // CONSTANTS

    // Positions
    public const POSITION_DEV_BACKEND  = 'dev_backend';
    public const POSITION_DEV_FRONTEND = 'dev_frontend';
    public const POSITION_TESTER       = 'tester';
    public const POSITION_COMTOR       = 'comtor';
    public const POSITION_BA           = 'BA';
    public const POSITION_QA           = 'QA';
    public const POSITION_PM           = 'PM';
    public const POSITION_PMO          = 'PMO';
    public const POSITION_ADMIN        = 'Admin';

    public const POSITIONS = [
        self::POSITION_DEV_BACKEND,
        self::POSITION_DEV_FRONTEND,
        self::POSITION_TESTER,
        self::POSITION_COMTOR,
        self::POSITION_BA,
        self::POSITION_QA,
        self::POSITION_PM,
        self::POSITION_PMO,
        self::POSITION_ADMIN,
    ];

    // Statuses
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ON_LEAVE = 'on_leave';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_ON_LEAVE,
    ];

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
