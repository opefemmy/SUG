<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = ['name', 'slug', 'code'];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}
