<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdCard extends Model
{
    protected $fillable = [
        'student_id',
        'card_number',
        'photo_path',
        'issue_date',
        'expiry_date',
        'status' // active, blocked, expired
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
