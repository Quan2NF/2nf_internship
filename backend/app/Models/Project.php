<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\Project\ProjectStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * Class Project
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 *
 * @property ProjectStatus $status
 *
 * @property Carbon|null $planned_start_date
 * @property Carbon|null $planned_end_date
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 *
 * @property int $progress_rate
 * @property bool $is_public
 * @property bool $is_active
 *
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property-read User $creator
 * @property-read User $updater
 *
 * @property-read Collection<int, User> $users
 * @property-read Collection<int, ProjectMember> $projectMembers
 * @property-read Wiki|null $wiki
 * @property-read Document|null $document
 */
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'projects';

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
            'code'               => 'string',
            'name'               => 'string',
            'description'        => 'string',
            'status'             => ProjectStatus::class,
            'planned_start_date' => 'date',
            'planned_end_date'   => 'date',
            'start_date'         => 'date',
            'end_date'           => 'date',
            'progress_rate'      => 'integer',
            'is_public'          => 'boolean',
            'is_active'          => 'boolean',
            'created_by'         => 'integer',
            'updated_by'         => 'integer',
        ];
    }

    protected $attributes = [
        'is_public' => false,
        'is_active' => true,
    ];

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
        return $this->belongsToMany(User::class, 'project_members')
                    ->withPivot('id')
                    ->withTimestamps();
    }

    public function projectMembers()
    {
        return $this->hasMany(ProjectMember::class, 'project_id');
    }

    public function wiki()
    {
        return $this->hasOne(Wiki::class, 'project_id');
    }

    public function document()
    {
        return $this->hasOne(Document::class, 'project_id');
    }

    public function version()
    {
        return $this->hasOne(Version::class, 'project_id');
    }
}
