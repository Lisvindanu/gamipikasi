<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Get all users
     */
    public function getAllUsers(?string $role = null, ?int $departmentId = null)
    {
        $query = User::with('department:id,name');

        if ($role) {
            $query->where('role', $role);
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $id)
    {
        return User::with(['department', 'badges'])
            ->findOrFail($id);
    }

    /**
     * Create a new user
     */
    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    /**
     * Update user
     */
    public function updateUser(int $id, array $data): User
    {
        $user = User::findOrFail($id);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $user->fresh(['department']);
    }

    /**
     * Delete user
     */
    public function deleteUser(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    /**
     * Get user dashboard data
     */
    public function getUserDashboard(int $userId): array
    {
        $user = User::with(['department', 'badges'])
            ->findOrFail($userId);

        $pointService = new PointService();
        $breakdown = $pointService->getUserPointBreakdown($userId);
        $recentPoints = $pointService->getUserPointHistory($userId);

        // Get user rank
        $rank = User::where('total_points', '>', $user->total_points)->count() + 1;
        $totalUsers = User::count();

        return [
            'user' => $user,
            'total_points' => $user->total_points,
            'rank' => $rank,
            'total_users' => $totalUsers,
            'point_breakdown' => $breakdown,
            'recent_points' => $recentPoints->take(10),
            'badges' => $user->badges,
        ];
    }

    /**
     * Get user's position in department
     */
    public function getUserDepartmentRank(int $userId): ?int
    {
        $user = User::findOrFail($userId);

        if (!$user->department_id) {
            return null;
        }

        return User::where('department_id', $user->department_id)
            ->where('total_points', '>', $user->total_points)
            ->count() + 1;
    }

    /**
     * Update user role
     */
    public function updateUserRole(int $userId, string $role): User
    {
        $user = User::findOrFail($userId);
        $user->update(['role' => $role]);

        return $user;
    }

    /**
     * Assign user to department
     */
    public function assignUserToDepartment(int $userId, int $departmentId): User
    {
        $user = User::findOrFail($userId);
        $user->update(['department_id' => $departmentId]);

        return $user->fresh(['department']);
    }
}
