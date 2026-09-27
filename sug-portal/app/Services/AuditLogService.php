<?php

namespace App\Services;

use App\Models\SystemAuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Log a system event.
     */
    public function logEvent(string $event, ?string $modelType = null, ?int $modelId = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        SystemAuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log a change to a specific model (Diff).
     */
    public function logModelChange(string $event, \Illuminate\Database\Eloquent\Model $model, array $oldValues = null): void
    {
        $newValues = $model->getAttributes();

        // Filter out timestamps to avoid noise
        unset($newValues['created_at'], $newValues['updated_at']);

        $this->logEvent(
            $event,
            get_class($model),
            $model->id,
            $oldValues,
            $newValues
        );
    }
}
