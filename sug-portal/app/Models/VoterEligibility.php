<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoterEligibility extends Model
{
    protected $fillable = ['election_id', 'student_id', 'is_eligible', 'reason'];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
