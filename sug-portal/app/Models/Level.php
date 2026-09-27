<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Level extends Model
{
    protected $fillable = ['programme_id', 'level_number'];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }
}
