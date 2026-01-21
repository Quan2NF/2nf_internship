<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WikiContent extends Model
{
    protected $table = 'wiki_contents';

    protected $fillable = ['wiki_id', 'content'];

    public function wiki(): BelongsTo
    {
        return $this->belongsTo(Wiki::class, 'wiki_id');
    }
}
