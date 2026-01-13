<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}

