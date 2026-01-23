<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $wiki_id
 * @property string $content
 * 
 * @property-read Wiki $wiki
 */
class WikiContent extends Model
{
    use HasFactory;

    protected $table = 'wiki_contents';

    protected $fillable = [
        'wiki_id',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'wiki_id' => 'integer',
            'content' => 'string',
        ];
    }

    public function wiki()
    {
        return $this->belongsTo(Wiki::class, 'wiki_id');
    }
}
