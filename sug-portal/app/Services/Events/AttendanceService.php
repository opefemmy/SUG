<?php

namespace App\Services\Events;

use App\Models\EventAttendance;
use App\Models\EventRegistration;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Record attendance for a student via QR scan.
     */
    public function markAttendance(int $eventId, int $studentId, ?string $scannedBy = null): bool
    {
        // 1. Verify student is registered for the event
        $registration = EventRegistration::where('event_id', $eventId)
            ->where('student_id', $studentId)
            ->first();

        if (!$registration) {
            throw new \Exception("Student is not registered for this event.");
        }

        // 2. Check if already attended
        if (EventAttendance::where('event_id', $eventId)
            ->where('student_id', $studentId)
            ->exists()) {
            throw new \Exception("Student has already been marked as attended.");
        }

        // 3. Record attendance
        EventAttendance::create([
            'event_id' => $eventId,
            'student_id' => $studentId,
            'attended_at' => now(),
            'scanned_by' => $scannedBy,
        ]);

        return true;
    }
}
