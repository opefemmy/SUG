<?php

namespace App\Services\Events;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class EventService
{
    /**
     * Register a student for an event.
     */
    public function registerStudent(Student $student, Event $event): EventRegistration
    {
        // Check if event is full
        if ($event->capacity && $event->registrations()->count() >= $event->capacity) {
            throw new \Exception("Event capacity has been reached.");
        }

        // Check if already registered
        if ($event->registrations()->where('student_id', $student->id)->exists()) {
            throw new \Exception("Student is already registered for this event.");
        }

        return EventRegistration::create([
            'event_id' => $event->id,
            'student_id' => $student->id,
            'registered_at' => now(),
            'payment_status' => 'not_required',
        ]);
    }

    /**
     * Get registration stats for an event.
     */
    public function getRegistrationStats(Event $event): array
    {
        $totalRegistered = $event->registrations()->count();
        $totalAttended = $event->attendances()->count();

        return [
            'total_registered' => $totalRegistered,
            'total_attended' => $totalAttended,
            'attendance_percentage' => $totalRegistered > 0
                ? round(($totalAttended / $totalRegistered) * 100, 2)
                : 0,
        ];
    }
}
