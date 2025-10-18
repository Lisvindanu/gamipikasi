<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\PointService;
use App\Services\BadgeService;
use App\Services\NotificationService;
use App\Services\ActivityLogService;
use App\Services\DeadlineReminderService;
use App\Models\Badge;
use App\Models\Task;
use App\Models\Notification;
use App\Models\TaskAttachment;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    protected UserService $userService;
    protected PointService $pointService;
    protected BadgeService $badgeService;
    protected NotificationService $notificationService;
    protected ActivityLogService $activityLogService;
    protected DeadlineReminderService $deadlineService;

    public function __construct(
        UserService $userService,
        PointService $pointService,
        BadgeService $badgeService,
        NotificationService $notificationService,
        ActivityLogService $activityLogService,
        DeadlineReminderService $deadlineService
    ) {
        $this->userService = $userService;
        $this->pointService = $pointService;
        $this->badgeService = $badgeService;
        $this->notificationService = $notificationService;
        $this->activityLogService = $activityLogService;
        $this->deadlineService = $deadlineService;
    }

    /**
     * Show Member Dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get user dashboard data
        $dashboard = $this->userService->getUserDashboard($user->id);

        // Get all badges for display
        $allBadges = Badge::all();

        // Get deadline data for widgets
        // For Member: show only tasks assigned to them
        $upcomingDeadlines = $this->deadlineService->getUpcomingDeadlines($user->id, 7);
        $overdueTasks = $this->deadlineService->getOverdueTasks($user->id);
        $deadlineStats = $this->deadlineService->getDeadlineStats($user->id);

        return view('member.dashboard', compact('user', 'dashboard', 'allBadges', 'upcomingDeadlines', 'overdueTasks', 'deadlineStats'));
    }

    /**
     * Show Task Board (Trello-style for members)
     */
    public function taskBoard()
    {
        $user = Auth::user();

        // Get user's assigned tasks
        $tasks = Task::with(['assignedBy', 'department', 'attachments.uploader', 'comments.user'])
            ->where('assigned_to', $user->id)
            ->orderBy('deadline')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group tasks by status
        $tasksByStatus = [
            'pending' => $tasks->where('status', 'pending'),
            'in_progress' => $tasks->where('status', 'in_progress'),
            'completed' => $tasks->where('status', 'completed'),
        ];

        return view('member.task-board', compact('tasksByStatus', 'tasks'));
    }

    /**
     * Update task status (member can mark as in_progress or completed)
     */
    public function updateTaskStatus(Request $request, Task $task)
    {
        $user = Auth::user();

        // Verify task is assigned to this user
        if ($task->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:in_progress,completed',
            'completion_note' => 'nullable|string|max:500',
        ]);

        try {
            $oldStatus = $task->status;
            $task->status = $validated['status'];
            if ($validated['status'] === 'completed') {
                $task->completion_note = $validated['completion_note'] ?? null;
                $task->completed_at = now();
            }
            $task->updated_at = now();
            $task->save();

            // Log status change
            if ($oldStatus !== $validated['status']) {
                $this->activityLogService->logTaskStatusChanged($user->id, $task, $oldStatus, $validated['status']);
            }

            // If task is completed, award points and check badges
            if ($validated['status'] === 'completed' && $task->point_reward) {
                // Award points using PointService
                $this->pointService->awardTaskCompletionPoints(
                    $task->assigned_to,
                    $task->point_reward,
                    $task->assigned_by,
                    $task->title
                );

                // Check and award badges
                $this->badgeService->checkAndAwardBadges($task->assigned_to);

                // Notify task assigner about completion
                $this->notificationService->createTaskCompletedNotification(
                    $task->assigned_by,
                    $task->id,
                    $task->title,
                    $user->name
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully!',
                'task' => $task->fresh()->load(['assignedBy', 'department']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Upload Task Attachment (Evidence/Proof)
     */
    public function uploadTaskAttachment(Request $request, Task $task)
    {
        $user = Auth::user();

        // Verify task is assigned to this user
        if ($task->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'caption' => 'nullable|string|max:500',
        ]);

        try {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->store('task-attachments', 'public');

            $attachment = TaskAttachment::create([
                'task_id' => $task->id,
                'uploaded_by' => $user->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'caption' => $validated['caption'] ?? null,
            ]);

            // Auto-update task status to in_progress if still pending
            if ($task->status === 'pending') {
                $task->status = 'in_progress';
                $task->updated_at = now();
                $task->save();
            }

            // Log activity
            $this->activityLogService->logAttachmentUploaded($user->id, $task, $fileName);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'attachment' => $attachment->load('uploader'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete Task Attachment
     */
    public function deleteTaskAttachment(TaskAttachment $attachment)
    {
        $user = Auth::user();

        // Verify attachment was uploaded by this user
        if ($attachment->uploaded_by !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            $fileName = $attachment->file_name;
            $task = $attachment->task;

            // Delete file from storage
            Storage::disk('public')->delete($attachment->file_path);

            // Delete database record
            $attachment->delete();

            // Log activity
            $this->activityLogService->logAttachmentDeleted($user->id, $task, $fileName);

            return response()->json([
                'success' => true,
                'message' => 'Attachment deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Add Comment to Task
     */
    public function addTaskComment(Request $request, Task $task)
    {
        $user = Auth::user();

        // Verify task is assigned to this user
        if ($task->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        try {
            $comment = TaskComment::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'comment' => $validated['comment'],
            ]);

            // Auto-update task status to in_progress if still pending
            if ($task->status === 'pending') {
                $task->status = 'in_progress';
                $task->updated_at = now();
                $task->save();
            }

            // Notify task assigner about comment
            $this->notificationService->createCommentAddedNotification(
                $task->assigned_by,
                $task->id,
                $task->title,
                $user->name
            );

            // Log activity
            $this->activityLogService->logCommentAdded($user->id, $task, $validated['comment']);

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment' => $comment->load('user'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * View Task Attachment (for preview/inline display)
     */
    public function viewAttachment(TaskAttachment $attachment)
    {
        $user = Auth::user();

        // Check if user has access to this attachment
        // User can view if: they uploaded it, OR the task is assigned to them, OR they assigned the task
        $task = $attachment->task;
        $hasAccess = ($attachment->uploaded_by === $user->id) ||
                     ($task->assigned_to === $user->id) ||
                     ($task->assigned_by === $user->id);

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to this attachment');
        }

        $path = Storage::disk('public')->path($attachment->file_path);

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->file($path);
    }

    /**
     * Download Task Attachment
     */
    public function downloadAttachment(TaskAttachment $attachment)
    {
        $user = Auth::user();

        // Check if user has access to this attachment
        $task = $attachment->task;
        $hasAccess = ($attachment->uploaded_by === $user->id) ||
                     ($task->assigned_to === $user->id) ||
                     ($task->assigned_by === $user->id);

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to this attachment');
        }

        $path = Storage::disk('public')->path($attachment->file_path);

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->download($path, $attachment->file_name);
    }

    /**
     * Get Notifications
     */
    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = $this->notificationService->getRecentNotifications($user->id, 20);
        $unreadCount = $this->notificationService->getUnreadCount($user->id);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark Notification as Read
     */
    public function markNotificationAsRead(Notification $notification)
    {
        $user = Auth::user();

        // Verify notification belongs to this user
        if ($notification->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->notificationService->markAsRead($notification->id);

        return response()->json(['success' => true]);
    }

    /**
     * Mark All Notifications as Read
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        $this->notificationService->markAllAsRead($user->id);

        return response()->json(['success' => true]);
    }
}
