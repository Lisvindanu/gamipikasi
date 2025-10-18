<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PointService;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PointController extends Controller
{
    protected PointService $pointService;
    protected BadgeService $badgeService;

    public function __construct(PointService $pointService, BadgeService $badgeService)
    {
        $this->pointService = $pointService;
        $this->badgeService = $badgeService;
    }

    /**
     * Add points to a user
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category' => 'required|in:commitment,collaboration,initiative,responsibility,violation',
            'value' => 'required|integer',
            'note' => 'nullable|string',
            'given_by' => 'required|exists:users,id',
        ]);

        try {
            $point = $this->pointService->addPoint(
                $validated['user_id'],
                $validated['category'],
                $validated['value'],
                $validated['given_by'],
                $validated['note'] ?? null
            );

            // Check and award badges
            $newBadges = $this->badgeService->checkAndAwardBadges($validated['user_id']);

            return response()->json([
                'success' => true,
                'message' => 'Points added successfully',
                'data' => $point,
                'new_badges' => $newBadges,
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get user point history
     */
    public function userHistory(Request $request, string $userId): JsonResponse
    {
        $history = $this->pointService->getUserPointHistory(
            $userId,
            $request->input('category')
        );

        return response()->json([
            'success' => true,
            'data' => $history,
        ]);
    }

    /**
     * Get user point breakdown
     */
    public function breakdown(string $userId): JsonResponse
    {
        $breakdown = $this->pointService->getUserPointBreakdown($userId);

        return response()->json([
            'success' => true,
            'data' => $breakdown,
        ]);
    }

    /**
     * Get leaderboard
     */
    public function leaderboard(Request $request): JsonResponse
    {
        $leaderboard = $this->pointService->getLeaderboard(
            $request->input('department_id'),
            $request->input('limit', 10)
        );

        return response()->json([
            'success' => true,
            'data' => $leaderboard,
        ]);
    }

    /**
     * Get department stats
     */
    public function departmentStats(string $departmentId): JsonResponse
    {
        $stats = $this->pointService->getDepartmentStats($departmentId);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
