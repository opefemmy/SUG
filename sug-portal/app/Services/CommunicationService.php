<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class CommunicationService
{
    /**
     * Create a general announcement and optionally notify targeted users.
     */
    public function createAnnouncement(array $data): Announcement
    {
        return DB::transaction(function () use ($data) {
            $announcement = Announcement::create($data);

            // If targeting is specified, create corresponding notifications
            $this->dispatchNotifications($announcement);

            return $announcement;
        });
    }

    /**
     * Handle notification dispatch based on announcement target.
     */
    protected function dispatchNotifications(Announcement $announcement): void
    {
        $query = User::query();

        if ($announcement->target_audience === 'students') {
            $query->whereHas('student');
        } elseif ($announcement->target_audience === 'staff') {
            $query->whereHas('role', function($q) { $q->where('name', 'admin'); });
        } elseif ($announcement->target_audience === 'specific_level') {
            $query->whereHas('student', function($q) use ($announcement) {
                $q->where('level_id', $announcement->target_level);
            });
        }

        $users = $query->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => "New Announcement: {$announcement->title}",
                'body' => substr($announcement->content, 0, 150) . '...',
                'action_url' => route('announcements.show', $announcement->id),
            ]);
        }
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(int $notificationId): void
    {
        Notification::where('id', $notificationId)
            ->where('user_id', auth()->id())
            ->update(['read_at' => now()]);
    }
}
