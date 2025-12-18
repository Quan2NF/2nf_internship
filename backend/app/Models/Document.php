<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'filename',
        'url',
        'uploaded_by',
    ];

    protected $casts = [
        'filename'    => 'string',
        'url'         => 'string',
        'uploaded_by' => 'integer',
    ];

    // RELATIONSHIPS

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
