<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'kickoff_date',
        'end_date',
        'description',
        'status',
        'pm_id',
    ];

    protected $casts = [
        'kickoff_date' => 'date',
        'end_date'     => 'date',
        'status'       => 'string',
    ];

    public const STATUS_PLANNED   = 'planned';
    public const STATUS_ACTIVE    = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_ARCHIVED  = 'archived';

    public const STATUSES = [
        self::STATUS_PLANNED,
        self::STATUS_ACTIVE,
        self::STATUS_COMPLETED,
        self::STATUS_ARCHIVED,
    ];

    // RELATIONSHIPS

    public function pm()
    {
        return $this->belongsTo(User::class, 'pm_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withPivot('role_in_project', 'joined_at')
                    ->withTimestamps();
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
