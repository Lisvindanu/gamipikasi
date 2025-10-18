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
use App\Models\Point;
use App\Models\Post;
use App\Models\Department;
use App\Models\UserBadge;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class LeadController extends Controller
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
     * Show Leadership Dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_members' => User::count(),
            'total_points' => User::sum('total_points'),
            'avg_points' => round(User::avg('total_points'), 1),
            'active_badges' => UserBadge::distinct('badge_id')->count(),
        ];

        // Get department performance
        $departmentPerformance = $this->departmentService->getAllDepartmentsPerformance();

        // Get leaderboard
        $leaderboard = $this->pointService->getLeaderboard(null, 10);

        // Get upcoming deadlines
        $upcomingDeadlines = $this->deadlineService->getUpcomingDeadlines(null, 7);
        $overdueTasks = $this->deadlineService->getOverdueTasks();
        $deadlineStats = $this->deadlineService->getDeadlineStats();

        return view('lead.dashboard', compact('stats', 'departmentPerformance', 'leaderboard', 'upcomingDeadlines', 'overdueTasks', 'deadlineStats'));
    }

    /**
     * Show Settings Page
     */
    public function settings()
    {
        $stats = [
            'total_members' => User::count(),
            'total_points_given' => Point::sum('value'),
            'total_assessments' => Point::count(),
            'total_posts' => Post::count(),
            'pinned_posts' => Post::where('is_pinned', true)->count(),
            'departments' => Department::count(),
        ];

        $pinnedPosts = Post::where('is_pinned', true)
            ->with(['author', 'department'])
            ->latest()
            ->get();

        $recentPosts = Post::with(['author', 'department'])
            ->latest()
            ->limit(10)
            ->get();

        return view('lead.settings', compact('stats', 'pinnedPosts', 'recentPosts'));
    }

    /**
     * Export Points Data
     */
    public function exportPoints()
    {
        $points = Point::with(['user', 'assessor'])
            ->orderBy('created_at', 'desc')
            ->get();

        $csv = "ID,User,Category,Points,Note,Assessed By,Date\n";

        foreach ($points as $point) {
            $csv .= sprintf(
                "%d,%s,%s,%d,%s,%s,%s\n",
                $point->id,
                $point->user->name ?? 'N/A',
                $point->category,
                $point->value,
                str_replace([",", "\n"], [";", " "], $point->note ?? ''),
                $point->assessor->name ?? 'System',
                $point->created_at->format('Y-m-d H:i:s')
            );
        }

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="points-export-' . date('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Export Members Data
     */
    public function exportMembers()
    {
        $users = User::with(['department', 'userBadges.badge'])
            ->orderBy('total_points', 'desc')
            ->get();

        $csv = "ID,Name,Email,Role,Department,Total Points,Badges Count\n";

        foreach ($users as $user) {
            $csv .= sprintf(
                "%d,%s,%s,%s,%s,%d,%d\n",
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $user->department->name ?? 'N/A',
                $user->total_points,
                $user->userBadges->count()
            );
        }

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="members-export-' . date('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Show Task Board (Trello-style)
     */
    public function taskBoard()
    {
        $user = Auth::user();

        // Get all tasks created by this lead
        $tasks = Task::with(['assignedTo', 'assignedBy', 'attachments', 'comments.user'])
            ->where('assigned_by', $user->id)
            ->orderBy('deadline')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group tasks by status
        $tasksByStatus = [
            'pending' => $tasks->where('status', 'pending'),
            'in_progress' => $tasks->where('status', 'in_progress'),
            'completed' => $tasks->where('status', 'completed'),
        ];

        // Get all assignable users (heads, co-lead, secretary, bendahara)
        $assignableUsers = User::whereIn('role', ['head', 'co-lead', 'secretary', 'bendahara'])
            ->orderBy('name')
            ->get();

        return view('lead.task-board', compact('tasksByStatus', 'assignableUsers', 'tasks'));
    }

    /**
     * Create Task (Lead assigns to Head/Secretary/Bendahara/Co-Lead)
     */
    public function createTask(Request $request)
    {
        $user = Auth::user();

        // Validate user is lead or co-lead
        if (!in_array($user->role, ['lead', 'co-lead'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only Lead/Co-Lead can create tasks',
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

        // Verify assigned user is head/secretary/bendahara/co-lead
        $assignedUser = User::findOrFail($validated['assigned_to']);
        if (!in_array($assignedUser->role, ['head', 'co-lead', 'secretary', 'bendahara'])) {
            return response()->json([
                'success' => false,
                'message' => 'You can only assign tasks to Heads, Co-Lead, Secretary, or Bendahara',
            ], 403);
        }

        try {
            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'department_id' => null, // Lead tasks are cross-department
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
     * Update Task Status (Lead)
     */
    public function updateTaskStatus(Request $request, Task $task)
    {
        $user = Auth::user();

        // Verify task was created by this lead
        if ($task->assigned_by !== $user->id) {
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
     * Show Activity Log
     */
    public function activityLog(Request $request)
    {
        $userId = $request->get('user_id');
        $action = $request->get('action');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $activities = $this->activityLogService->getFilteredActivities(
            $userId,
            $action,
            $dateFrom,
            $dateTo,
            100
        );

        $stats = $this->activityLogService->getActivityStats();

        // Get all users for filter dropdown
        $users = User::orderBy('name')->get(['id', 'name', 'role']);

        return view('lead.activity-log', compact('activities', 'stats', 'users'));
    }

    /**
     * Delete Task (Lead)
     */
    public function deleteTask(Task $task)
    {
        $user = Auth::user();

        // Verify task was created by this lead
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
     * Show User Management Page
     */
    public function manageUsers()
    {
        $users = User::with('department')->orderBy('role')->orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('lead.users', compact('users', 'departments'));
    }

    /**
     * Update User Role
     */
    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:lead,co-lead,secretary,bendahara,head,member',
        ]);

        try {
            $user->update(['role' => $validated['role']]);

            return response()->json([
                'success' => true,
                'message' => 'User role updated successfully',
                'user' => $user->load('department'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update User Department
     */
    public function updateUserDepartment(Request $request, User $user)
    {
        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
        ]);

        try {
            $user->update(['department_id' => $validated['department_id']]);

            return response()->json([
                'success' => true,
                'message' => 'User department updated successfully',
                'user' => $user->load('department'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
