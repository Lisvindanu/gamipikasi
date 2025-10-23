<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\DepartmentService;
use App\Services\PointService;
use App\Services\BadgeService;
use App\Services\NotificationService;
use App\Services\ActivityLogService;
use App\Services\DeadlineReminderService;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\TaskComment;
use App\Mail\TaskAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class HeadController extends Controller
{
    protected DepartmentService $departmentService;
    protected PointService $pointService;
    protected BadgeService $badgeService;
    protected NotificationService $notificationService;
    protected ActivityLogService $activityLogService;
    protected DeadlineReminderService $deadlineService;

    public function __construct(
        DepartmentService $departmentService,
        PointService $pointService,
        BadgeService $badgeService,
        NotificationService $notificationService,
        ActivityLogService $activityLogService,
        DeadlineReminderService $deadlineService
    ) {
        $this->departmentService = $departmentService;
        $this->pointService = $pointService;
        $this->badgeService = $badgeService;
        $this->notificationService = $notificationService;
        $this->activityLogService = $activityLogService;
        $this->deadlineService = $deadlineService;
    }

    /**
     * Show Head of Department Dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $departmentId = $user->department_id;

        // Get department info
        $department = $this->departmentService->getDepartmentById($departmentId);

        // Get department performance
        $performance = $this->departmentService->getDepartmentPerformance($departmentId);

        // Get department members
        $members = $this->departmentService->getDepartmentMembers($departmentId);

        // Get department stats
        $stats = $this->pointService->getDepartmentStats($departmentId);

        // Get tasks for this department
        $tasks = Task::with(['assignedTo', 'assignedBy'])
            ->where('department_id', $departmentId)
            ->orderBy('deadline')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get deadline data for widgets
        // For Head: show deadlines for tasks they're assigned to OR tasks in their department
        $upcomingDeadlines = $this->deadlineService->getUpcomingDeadlines(null, 7)
            ->filter(function($task) use ($user, $departmentId) {
                return $task->assigned_to == $user->id || $task->department_id == $departmentId;
            });

        $overdueTasks = $this->deadlineService->getOverdueTasks()
            ->filter(function($task) use ($user, $departmentId) {
                return $task->assigned_to == $user->id || $task->department_id == $departmentId;
            });

        // Calculate deadline stats for this head's scope
        $allDepartmentTasks = Task::where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('deadline')
            ->where(function($query) use ($user, $departmentId) {
                $query->where('assigned_to', $user->id)
                      ->orWhere('department_id', $departmentId);
            })
            ->get();

        $deadlineStats = [
            'overdue' => $allDepartmentTasks->where('deadline', '<', now())->count(),
            'due_today' => $allDepartmentTasks->whereBetween('deadline', [now()->startOfDay(), now()->endOfDay()])->count(),
            'due_this_week' => $allDepartmentTasks->whereBetween('deadline', [now(), now()->endOfWeek()])->count(),
            'due_this_month' => $allDepartmentTasks->whereBetween('deadline', [now(), now()->endOfMonth()])->count(),
        ];

        return view('head.dashboard', compact('department', 'performance', 'members', 'stats', 'tasks', 'upcomingDeadlines', 'overdueTasks', 'deadlineStats'));
    }

    /**
     * Show Task Board (Trello-style)
     */
    public function taskBoard()
    {
        $user = Auth::user();

        // Get tasks assigned to this head (from Lead) - include all statuses
        $myTasks = Task::with(['assignedBy', 'assignedTo', 'attachments', 'comments.user'])
            ->where('assigned_to', $user->id)
            ->orderBy('deadline')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all tasks created by this head for their department members
        $departmentTasks = Task::with(['assignedTo', 'assignedBy', 'attachments', 'comments.user'])
            ->where('assigned_by', $user->id)
            ->orderBy('deadline')
            ->orderBy('created_at', 'desc')
            ->get();

        // Merge both collections for JavaScript access
        $tasks = $myTasks->merge($departmentTasks);

        // Group department tasks by status
        $tasksByStatus = [
            'pending' => $departmentTasks->where('status', 'pending'),
            'in_progress' => $departmentTasks->where('status', 'in_progress'),
            'completed' => $departmentTasks->where('status', 'completed'),
        ];

        // Get assignable users (members in same department)
        $assignableUsers = User::where('department_id', $user->department_id)
            ->where('role', 'member')
            ->orderBy('name')
            ->get();

        return view('head.task-board', compact('tasksByStatus', 'assignableUsers', 'tasks', 'myTasks'));
    }

    /**
     * Create a new task
     */
    public function createTask(Request $request)
    {
        $user = Auth::user();

        // Validate user is a head
        if ($user->role !== 'head') {
            return response()->json([
                'success' => false,
                'message' => 'Only department heads can create tasks',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date|after:today',
            'point_reward' => 'nullable|integer|min:0|max:50',
        ]);

        // Verify assigned user is in same department
        $assignedUser = User::findOrFail($validated['assigned_to']);
        if ($assignedUser->department_id !== $user->department_id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only assign tasks to members of your department',
            ], 403);
        }

        try {
            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'department_id' => $user->department_id,
                'assigned_by' => $user->id,
                'assigned_to' => $validated['assigned_to'],
                'priority' => $validated['priority'],
                'deadline' => $validated['deadline'] ?? null,
                'point_reward' => $validated['point_reward'] ?? null,
                'status' => 'pending',
            ]);

            // Send notification to assigned user
            $this->notificationService->createTaskAssignedNotification(
                $validated['assigned_to'],
                $task->id,
                $task->title,
                $user->name
            );

            // Send email notification
            Mail::to($assignedUser->email)->send(new TaskAssigned($task, $assignedUser, $user));

            // Log activity
            $this->activityLogService->logTaskCreated($user->id, $task);

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $task->load(['assignedTo', 'assignedBy']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update task status
     */
    public function updateTaskStatus(Request $request, Task $task)
    {
        $user = Auth::user();

        // Verify task belongs to head's department OR is assigned to this head
        $isOwnTask = ($task->assigned_to == $user->id); // Task from Lead
        $isDepartmentTask = ($task->department_id == $user->department_id); // Task created by this head

        if (!$isOwnTask && !$isDepartmentTask) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        try {
            $oldStatus = $task->status;
            $task->status = $validated['status'];
            if ($validated['status'] === 'completed') {
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
                $assignedUser = $task->assignedTo;
                $this->notificationService->createTaskCompletedNotification(
                    $task->assigned_by,
                    $task->id,
                    $task->title,
                    $assignedUser->name
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Task status updated',
                'task' => $task->fresh()->load(['assignedTo', 'assignedBy']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete a task
     */
    public function deleteTask(Task $task)
    {
        $user = Auth::user();

        // Verify task belongs to head's department and was created by this head
        if ($task->assigned_by !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete tasks you created',
            ], 403);
        }

        // Can't delete completed tasks
        if ($task->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete completed tasks',
            ], 400);
        }

        try {
            $taskTitle = $task->title;
            $task->delete();

            // Log activity
            $this->activityLogService->logTaskDeleted($user->id, $taskTitle);

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Upload Task Attachment (Evidence/Proof) - for tasks assigned to this head
     */
    public function uploadTaskAttachment(Request $request, Task $task)
    {
        $user = Auth::user();

        // Verify task is assigned to this user
        if ($task->assigned_to != $user->id) {
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
        if ($attachment->uploaded_by != $user->id) {
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
        if ($task->assigned_to != $user->id) {
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
}
