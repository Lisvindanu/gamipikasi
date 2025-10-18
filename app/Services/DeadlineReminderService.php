<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DeadlineReminderService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get tasks with upcoming deadlines
     */
    public function getUpcomingDeadlines(?int $userId = null, int $daysAhead = 7): Collection
    {
        $query = Task::with(['assignedTo', 'assignedBy', 'department'])
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('deadline')
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addDays($daysAhead))
            ->orderBy('deadline', 'asc');

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        return $query->get()->map(function ($task) {
            $task->urgency = $this->calculateUrgency($task->deadline);
            $task->urgency_color = $this->getUrgencyColor($task->urgency);
            $task->urgency_label = $this->getUrgencyLabel($task->urgency);
            $task->days_remaining = now()->diffInDays($task->deadline, false);
            $task->hours_remaining = now()->diffInHours($task->deadline, false);
            return $task;
        });
    }

    /**
     * Get overdue tasks
     */
    public function getOverdueTasks(?int $userId = null): Collection
    {
        $query = Task::with(['assignedTo', 'assignedBy', 'department'])
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->orderBy('deadline', 'asc');

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        return $query->get()->map(function ($task) {
            $task->days_overdue = now()->diffInDays($task->deadline);
            return $task;
        });
    }

    /**
     * Calculate urgency level (1-5)
     * 5 = Critical (< 24 hours)
     * 4 = Very Urgent (1-2 days)
     * 3 = Urgent (3-5 days)
     * 2 = Soon (6-7 days)
     * 1 = Normal (> 7 days)
     */
    public function calculateUrgency(Carbon $deadline): int
    {
        $hoursRemaining = now()->diffInHours($deadline, false);

        if ($hoursRemaining < 0) {
            return 5; // Overdue
        }

        if ($hoursRemaining <= 24) {
            return 5; // Critical - less than 24 hours
        }

        if ($hoursRemaining <= 48) {
            return 4; // Very urgent - 1-2 days
        }

        $daysRemaining = now()->diffInDays($deadline, false);

        if ($daysRemaining <= 5) {
            return 3; // Urgent - 3-5 days
        }

        if ($daysRemaining <= 7) {
            return 2; // Soon - 6-7 days
        }

        return 1; // Normal - more than 7 days
    }

    /**
     * Get color based on urgency
     */
    public function getUrgencyColor(int $urgency): string
    {
        return match($urgency) {
            5 => 'var(--google-red)',      // Critical/Overdue
            4 => '#f57c00',                // Very urgent (orange)
            3 => 'var(--google-yellow)',   // Urgent
            2 => 'var(--google-green)',    // Soon
            default => 'var(--text-secondary)', // Normal
        };
    }

    /**
     * Get label based on urgency
     */
    public function getUrgencyLabel(int $urgency): string
    {
        return match($urgency) {
            5 => 'Critical',
            4 => 'Very Urgent',
            3 => 'Urgent',
            2 => 'Soon',
            default => 'Normal',
        };
    }

    /**
     * Send reminder notifications for upcoming deadlines
     * This should be called by a scheduler/cron job
     */
    public function sendDeadlineReminders(): int
    {
        $count = 0;

        // Get tasks due in 24 hours
        $criticalTasks = Task::with(['assignedTo'])
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('deadline')
            ->whereBetween('deadline', [now(), now()->addHours(24)])
            ->get();

        foreach ($criticalTasks as $task) {
            $this->notificationService->createDeadlineReminderNotification(
                $task->assigned_to,
                $task->id,
                'Urgent Deadline',
                "⚠️ Urgent: Task '{$task->title}' is due in less than 24 hours!",
                [
                    'task_title' => $task->title,
                    'deadline' => $task->deadline->format('Y-m-d H:i'),
                    'hours_remaining' => now()->diffInHours($task->deadline, false),
                ]
            );
            $count++;
        }

        // Get tasks due in 3 days
        $upcomingTasks = Task::with(['assignedTo'])
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('deadline')
            ->whereBetween('deadline', [now()->addHours(24), now()->addDays(3)])
            ->get();

        foreach ($upcomingTasks as $task) {
            $this->notificationService->createDeadlineReminderNotification(
                $task->assigned_to,
                $task->id,
                'Upcoming Deadline',
                "📅 Reminder: Task '{$task->title}' is due in " . now()->diffInDays($task->deadline) . " days",
                [
                    'task_title' => $task->title,
                    'deadline' => $task->deadline->format('Y-m-d H:i'),
                    'days_remaining' => now()->diffInDays($task->deadline, false),
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Get deadline statistics
     */
    public function getDeadlineStats(?int $userId = null): array
    {
        $query = Task::where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('deadline');

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        $tasks = $query->get();

        return [
            'overdue' => $tasks->where('deadline', '<', now())->count(),
            'due_today' => $tasks->whereBetween('deadline', [now()->startOfDay(), now()->endOfDay()])->count(),
            'due_this_week' => $tasks->whereBetween('deadline', [now(), now()->endOfWeek()])->count(),
            'due_this_month' => $tasks->whereBetween('deadline', [now(), now()->endOfMonth()])->count(),
        ];
    }
}
