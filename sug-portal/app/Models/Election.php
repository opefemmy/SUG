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

    public function positions(): HasMany
    {
        return $this->hasMany(ElectionPosition::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }
}
