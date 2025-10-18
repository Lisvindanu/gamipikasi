<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Task;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log an activity
     */
    public function log(
        int $userId,
        string $action,
        string $description,
        ?int $taskId = null,
        ?array $metadata = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $userId,
            'task_id' => $taskId,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log task creation
     */
    public function logTaskCreated(int $userId, Task $task): ActivityLog
    {
        return $this->log(
            $userId,
            'task_created',
            "Created task: {$task->title}",
            $task->id,
            [
                'task_title' => $task->title,
                'assigned_to' => $task->assigned_to,
                'priority' => $task->priority,
                'deadline' => $task->deadline?->format('Y-m-d'),
            ]
        );
    }

    /**
     * Log task status change
     */
    public function logTaskStatusChanged(int $userId, Task $task, string $oldStatus, string $newStatus): ActivityLog
    {
        return $this->log(
            $userId,
            'task_status_changed',
            "Changed task status from '{$oldStatus}' to '{$newStatus}'",
            $task->id,
            [
                'task_title' => $task->title,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]
        );
    }

    /**
     * Log task completion
     */
    public function logTaskCompleted(int $userId, Task $task): ActivityLog
    {
        return $this->log(
            $userId,
            'task_completed',
            "Completed task: {$task->title}",
            $task->id,
            [
                'task_title' => $task->title,
                'point_reward' => $task->point_reward,
            ]
        );
    }

    /**
     * Log comment added
     */
    public function logCommentAdded(int $userId, Task $task, string $comment): ActivityLog
    {
        return $this->log(
            $userId,
            'comment_added',
            "Added comment on task: {$task->title}",
            $task->id,
            [
                'task_title' => $task->title,
                'comment_preview' => substr($comment, 0, 100),
            ]
        );
    }

    /**
     * Log attachment uploaded
     */
    public function logAttachmentUploaded(int $userId, Task $task, string $fileName): ActivityLog
    {
        return $this->log(
            $userId,
            'attachment_uploaded',
            "Uploaded attachment '{$fileName}' to task: {$task->title}",
            $task->id,
            [
                'task_title' => $task->title,
                'file_name' => $fileName,
            ]
        );
    }

    /**
     * Log attachment deleted
     */
    public function logAttachmentDeleted(int $userId, Task $task, string $fileName): ActivityLog
    {
        return $this->log(
            $userId,
            'attachment_deleted',
            "Deleted attachment '{$fileName}' from task: {$task->title}",
            $task->id,
            [
                'task_title' => $task->title,
                'file_name' => $fileName,
            ]
        );
    }

    /**
     * Log task deleted
     */
    public function logTaskDeleted(int $userId, string $taskTitle): ActivityLog
    {
        return $this->log(
            $userId,
            'task_deleted',
            "Deleted task: {$taskTitle}",
            null,
            [
                'task_title' => $taskTitle,
            ]
        );
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(int $limit = 50): Collection
    {
        return ActivityLog::with(['user', 'task'])
            ->recent($limit)
            ->get();
    }

    /**
     * Get activities for a specific user
     */
    public function getUserActivities(int $userId, int $limit = 50): Collection
    {
        return ActivityLog::with(['user', 'task'])
            ->forUser($userId)
            ->recent($limit)
            ->get();
    }

    /**
     * Get activities for a specific task
     */
    public function getTaskActivities(int $taskId): Collection
    {
        return ActivityLog::with(['user', 'task'])
            ->forTask($taskId)
            ->recent(100)
            ->get();
    }

    /**
     * Get activities by action type
     */
    public function getActivitiesByAction(string $action, int $limit = 50): Collection
    {
        return ActivityLog::with(['user', 'task'])
            ->byAction($action)
            ->recent($limit)
            ->get();
    }

    /**
     * Get activity statistics
     */
    public function getActivityStats(): array
    {
        return [
            'total_activities' => ActivityLog::count(),
            'today_activities' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week_activities' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_activities' => ActivityLog::whereMonth('created_at', now()->month)->count(),
            'most_active_users' => ActivityLog::selectRaw('user_id, count(*) as activity_count')
                ->groupBy('user_id')
                ->orderBy('activity_count', 'desc')
                ->with('user')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Get activities with filters
     */
    public function getFilteredActivities(
        ?int $userId = null,
        ?string $action = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        int $limit = 100
    ): Collection {
        $query = ActivityLog::with(['user', 'task']);

        if ($userId) {
            $query->forUser($userId);
        }

        if ($action) {
            $query->byAction($action);
        }

        if ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('created_at', '<=', $dateTo);
        }

        return $query->recent($limit)->get();
    }
}
