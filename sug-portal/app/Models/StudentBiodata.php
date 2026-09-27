<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentBiodata extends Model
{
    protected $table = 'student_biodata';

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone_number',
        'house_address',
        'parent_name',
        'parent_phone',
        'parent_email',
        'passport_path',
        'is_completed'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
