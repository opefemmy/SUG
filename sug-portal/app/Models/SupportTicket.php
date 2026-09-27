<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = [
        'student_id',
        'category_id',
        'subject',
        'description',
        'ticket_number',
        'status',
        'assigned_to'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SupportCategory::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }
}
