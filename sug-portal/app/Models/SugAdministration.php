<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SugAdministration extends Model
{
    protected $fillable = ['term_name', 'start_date', 'end_date', 'status'];

    public function officers(): HasMany
    {
        return $this->hasMany(SugOfficer::class);
    }
}
