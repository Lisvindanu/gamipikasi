<?php

namespace App\Services;

use App\Models\Activity;
use Carbon\Carbon;

class ActivityService
{
    /**
     * Get all activities
     */
    public function getAllActivities(?int $departmentId = null, ?string $month = null)
    {
        $query = Activity::with('department:id,name');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($month) {
            $date = Carbon::parse($month);
            $query->whereYear('date', $date->year)
                ->whereMonth('date', $date->month);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Get activity by ID
     */
    public function getActivityById(int $id)
    {
        return Activity::with('department')->findOrFail($id);
    }

    /**
     * Create a new activity
     */
    public function createActivity(array $data): Activity
    {
        return Activity::create($data);
    }

    /**
     * Update activity
     */
    public function updateActivity(int $id, array $data): Activity
    {
        $activity = Activity::findOrFail($id);
        $activity->update($data);

        return $activity->fresh(['department']);
    }

    /**
     * Delete activity
     */
    public function deleteActivity(int $id): void
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();
    }

    /**
     * Get upcoming activities
     */
    public function getUpcomingActivities(?int $departmentId = null, int $limit = 5)
    {
        $query = Activity::with('department:id,name')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get past activities
     */
    public function getPastActivities(?int $departmentId = null, int $limit = 10)
    {
        $query = Activity::with('department:id,name')
            ->where('date', '<', now())
            ->orderBy('date', 'desc');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get department activity statistics
     */
    public function getDepartmentActivityStats(int $departmentId, ?int $year = null): array
    {
        $year = $year ?? now()->year;

        $totalActivities = Activity::where('department_id', $departmentId)
            ->whereYear('date', $year)
            ->count();

        $activitiesByMonth = Activity::where('department_id', $departmentId)
            ->whereYear('date', $year)
            ->selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        return [
            'department_id' => $departmentId,
            'year' => $year,
            'total_activities' => $totalActivities,
            'activities_by_month' => $activitiesByMonth,
        ];
    }
}
