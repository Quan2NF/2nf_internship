<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    const STATUS_PLANNING = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_ON_HOLD = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_CANCELLED = 5;

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

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PLANNING => 'Planning',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_ON_HOLD => 'On Hold',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Unknown',
        };
    }
}
