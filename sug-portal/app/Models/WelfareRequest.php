<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WelfareRequest extends Model
{
    protected $fillable = [
        'student_id',
        'category_id',
        'subject',
        'description',
        'priority',
        'status'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(WelfareCategory::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(WelfareAttachment::class);
    }
}
