<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'project_id'  => 'integer',
            'title'       => 'string',
            'description' => 'string',
            'created_by'  => 'integer',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
