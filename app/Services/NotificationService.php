<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function createTaskAssignedNotification(int $userId, int $taskId, string $taskTitle, string $assignerName): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => 'task_assigned',
            'title' => 'New Task Assigned',
            'message' => "{$assignerName} assigned you a task: {$taskTitle}",
            'data' => ['task_id' => $taskId],
        ]);
    }

    public function createTaskCompletedNotification(int $userId, int $taskId, string $taskTitle, string $completedBy): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => 'task_completed',
            'title' => 'Task Completed',
            'message' => "{$completedBy} completed task: {$taskTitle}",
            'data' => ['task_id' => $taskId],
        ]);
    }

    public function createCommentAddedNotification(int $userId, int $taskId, string $taskTitle, string $commenterName): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => 'comment_added',
            'title' => 'New Comment',
            'message' => "{$commenterName} commented on: {$taskTitle}",
            'data' => ['task_id' => $taskId],
        ]);
    }

    public function createDeadlineReminderNotification(int $userId, int $taskId, string $title, string $message, ?array $data = null): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => 'deadline_reminder',
            'title' => $title,
            'message' => $message,
            'data' => array_merge(['task_id' => $taskId], $data ?? []),
        ]);
    }

    public function getUnreadCount(int $userId): int
    {
        return Notification::forUser($userId)->unread()->count();
    }

    public function getRecentNotifications(int $userId, int $limit = 10)
    {
        return Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function markAsRead(int $notificationId): void
    {
        $notification = Notification::find($notificationId);
        $notification?->markAsRead();
    }

    public function markAllAsRead(int $userId): void
    {
        Notification::forUser($userId)
            ->unread()
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);
    }
}
