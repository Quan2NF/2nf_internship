<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ProjectStatus;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
        'planned_start_date',
        'planned_end_date',
        'start_date',
        'end_date',
        'progress_rate',
        'is_public',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'planned_start_date' => 'date',
            'planned_end_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'progress_rate' => 'decimal:2',
            'is_public' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')
                    ->withTimestamps();
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function openIssues()
    {
        return $this->issues()->open();
    }

    public function closedIssues()
    {
        return $this->issues()->closed();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
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
    public function getStatusLabelAttribute(): string
    {
        if ($this->status instanceof ProjectStatus) {
            return $this->status->label();
        }

        // Fallback for legacy string statuses
        return match($this->status) {
            'planning' => 'Planning',
            'active' => 'Active',
            'completed' => 'Completed',
            'archived' => 'Archived',
            default => 'Unknown',
        };
    }
}
