<?php

namespace App\Models;

use App\Enums\Position\PositionScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'scope',
    ];

    protected function casts(): array
    {
        return [
            'scope' => PositionScope::class,
        ];
    }

    protected $attributes = [
        'scope' => PositionScope::Project,
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_positions')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }
}
