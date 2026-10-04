<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Election extends Model
{
    protected $fillable = [
        'name',
        'description',
        'session_id',
        'start_date',
        'end_date',
        'status' // Draft, Scheduled, Open, Closed, Published
    ];

    public function isLive(): bool
    {
        return $this->status === 'Open' &&
               now()->between($this->start_date, $this->end_date);
    }

    public function isAccreditable(): bool
    {
        if ($this->status !== 'Open') {
            return false;
        }

        if (!$this->accreditation_start || !$this->accreditation_end) {
            // Fallback: if dates aren't set, allow accreditation anytime it's 'Open'
            return true;
        }

        return now()->between($this->accreditation_start, $this->accreditation_end);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(ElectionPosition::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }
}
