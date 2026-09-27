<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WelfareAttachment extends Model
{
    protected $fillable = ['welfare_request_id', 'file_path', 'file_name'];

    public function request(): BelongsTo
    {
        return $this->belongsTo(WelfareRequest::class);
    }
}
