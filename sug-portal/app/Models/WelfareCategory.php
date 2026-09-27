<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WelfareCategory extends Model
{
    protected $fillable = ['name', 'description'];

    public function requests(): HasMany
    {
        return $this->hasMany(WelfareRequest::class);
    }
}
