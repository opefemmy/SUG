<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsImage extends Model
{
    protected $fillable = ['news_id', 'image_path', 'caption'];

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}
