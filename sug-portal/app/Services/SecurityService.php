<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SecurityService
{
    /**
     * Enforce password complexity rules.
     */
    public function validatePasswordStrength(string $password): bool
    {
        $hasUppercase = preg_match('@[A-Z]@', $password);
        $hasLowercase = preg_match('@[a-z]@', $password);
        $hasNumber = preg_match('@[0-9]@', $password);
        $hasSpecial = preg_match('@[^\w]@', $password);

        return $hasUppercase && $hasLowercase && $hasNumber && $hasSpecial && strlen($password) >= 8;
    }

    /**
     * Log security warnings for suspicious activities.
     */
    public function reportSuspiciousActivity(User $user, string $activity): void
    {
        Log::warning("SECURITY ALERT: Suspicious activity detected for User ID {$user->id}: {$activity}", [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Here we could also trigger an email alert to the main Admin
    }
}
