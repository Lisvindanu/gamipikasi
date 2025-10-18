<?php

namespace App\Services;

use App\Models\Department;
use App\Models\User;

class DepartmentService
{
    /**
     * Get all departments
     */
    public function getAllDepartments()
    {
        return Department::with('head:id,name,email')->get();
    }

    /**
     * Get department by ID
     */
    public function getDepartmentById(int $id)
    {
        return Department::with(['head:id,name,email', 'members'])
            ->findOrFail($id);
    }

    /**
     * Create a new department
     */
    public function createDepartment(array $data): Department
    {
        return Department::create($data);
    }

    /**
     * Update department
     */
    public function updateDepartment(int $id, array $data): Department
    {
        $department = Department::findOrFail($id);
        $department->update($data);

        return $department;
    }

    /**
     * Delete department
     */
    public function deleteDepartment(int $id): void
    {
        $department = Department::findOrFail($id);
        $department->delete();
    }

    /**
     * Get department members
     */
    public function getDepartmentMembers(int $departmentId)
    {
        return User::where('department_id', $departmentId)
            ->select('id', 'name', 'email', 'role', 'total_points')
            ->orderBy('total_points', 'desc')
            ->get();
    }

    /**
     * Get department performance summary
     */
    public function getDepartmentPerformance(int $departmentId): array
    {
        $department = Department::with('members')->findOrFail($departmentId);

        $totalMembers = $department->members->count();
        $totalPoints = $department->members->sum('total_points');
        $averagePoints = $totalMembers > 0 ? $totalPoints / $totalMembers : 0;

        return [
            'department_id' => $department->id,
            'department_name' => $department->name,
            'total_members' => $totalMembers,
            'total_points' => $totalPoints,
            'average_points' => round($averagePoints, 2),
            'top_performer' => $department->members->sortByDesc('total_points')->first(),
        ];
    }

    /**
     * Get all departments performance comparison
     */
    public function getAllDepartmentsPerformance(): array
    {
        $departments = Department::with('members')->get();

        return $departments->map(function ($department) {
            $totalMembers = $department->members->count();
            $totalPoints = $department->members->sum('total_points');

            return [
                'department_id' => $department->id,
                'department_name' => $department->name,
                'total_members' => $totalMembers,
                'total_points' => $totalPoints,
                'average_points' => $totalMembers > 0 ? round($totalPoints / $totalMembers, 2) : 0,
            ];
        })->sortByDesc('average_points')->values()->toArray();
    }

    /**
     * Assign head to department
     */
    public function assignHead(int $departmentId, int $userId): Department
    {
        $department = Department::findOrFail($departmentId);
        $user = User::findOrFail($userId);

        // Update user role to head
        $user->update(['role' => 'head', 'department_id' => $departmentId]);

        // Update department
        $department->update(['head_id' => $userId]);

        return $department->fresh(['head']);
    }
}
