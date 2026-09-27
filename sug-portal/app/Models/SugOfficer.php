<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SugOfficer extends Model
{
    protected $fillable = ['sug_administration_id', 'user_id', 'position', 'appointment_date', 'portfolio', 'image_path'];

    public function administration(): BelongsTo
    {
        return $this->belongsTo(SugAdministration::class, 'sug_administration_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
