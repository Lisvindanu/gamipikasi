<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Point;
use App\Services\PointService;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HRController extends Controller
{
    protected PointService $pointService;
    protected BadgeService $badgeService;

    public function __construct(PointService $pointService, BadgeService $badgeService)
    {
        $this->pointService = $pointService;
        $this->badgeService = $badgeService;
    }

    /**
     * Show HR Dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user is from HR department (department_id = 1)
        if ($user->department_id != 1) {
            abort(403, 'Only HR team members can access this dashboard');
        }

        // Get statistics
        $stats = [
            'total_members' => User::where('role', 'member')->count(),
            'total_points' => Point::sum('value'),
            'avg_points' => round(User::avg('total_points'), 1),
            'assessments_month' => Point::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Get all members with their departments
        $members = User::with('department')
            ->whereIn('role', ['member', 'head'])
            ->orderBy('total_points', 'desc')
            ->get();

        // Get all departments
        $departments = Department::all();

        return view('hr.dashboard', compact('stats', 'members', 'departments'));
    }

    /**
     * Add point to a user
     */
    public function addPoint(Request $request)
    {
        $user = Auth::user();

        // Check if user is from HR department (department_id = 1)
        if ($user->department_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Only HR team members can assign points',
            ], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category' => 'required|in:commitment,collaboration,initiative,responsibility,violation',
            'value' => 'required|integer',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            // Add point using service
            $point = $this->pointService->addPoint(
                $validated['user_id'],
                $validated['category'],
                $validated['value'],
                Auth::id(),
                $validated['note'] ?? null
            );

            // Check and award badges
            $newBadges = $this->badgeService->checkAndAwardBadges($validated['user_id']);

            return response()->json([
                'success' => true,
                'message' => 'Point added successfully',
                'point' => $point,
                'new_badges' => $newBadges,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Bulk add points to multiple users
     */
    public function bulkAddPoints(Request $request)
    {
        $user = Auth::user();

        // Check if user is from HR department (department_id = 1)
        if ($user->department_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya tim HR yang dapat mengatur poin',
            ], 403);
        }

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'category' => 'required|in:commitment,collaboration,initiative,responsibility,violation',
            'value' => 'required|integer',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            $results = [];
            $errors = [];

            foreach ($validated['user_ids'] as $userId) {
                try {
                    $point = $this->pointService->addPoint(
                        $userId,
                        $validated['category'],
                        $validated['value'],
                        Auth::id(),
                        $validated['note'] ?? null
                    );

                    // Check and award badges
                    $newBadges = $this->badgeService->checkAndAwardBadges($userId);

                    $results[] = [
                        'user_id' => $userId,
                        'success' => true,
                        'new_badges' => $newBadges,
                    ];
                } catch (\Exception $e) {
                    $errors[] = [
                        'user_id' => $userId,
                        'error' => $e->getMessage(),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Bulk points berhasil diberikan ke ' . count($results) . ' member',
                'results' => $results,
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
