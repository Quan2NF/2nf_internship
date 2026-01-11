<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position_id',
    ];

    /**
     * Relationship: User Position thuộc về User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: User Position thuộc về Position
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
