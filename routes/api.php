<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\PointController;
use App\Http\Controllers\Api\BadgeController;
use App\Http\Controllers\Api\ActivityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Users
    Route::apiResource('users', UserController::class);
    Route::get('users/{id}/dashboard', [UserController::class, 'dashboard']);

    // Departments
    Route::apiResource('departments', DepartmentController::class);
    Route::get('departments/{id}/members', [DepartmentController::class, 'members']);
    Route::get('departments/performance/all', [DepartmentController::class, 'performance']);

    // Points
    Route::post('points', [PointController::class, 'store']);
    Route::get('points/users/{userId}/history', [PointController::class, 'userHistory']);
    Route::get('points/users/{userId}/breakdown', [PointController::class, 'breakdown']);
    Route::get('points/leaderboard', [PointController::class, 'leaderboard']);
    Route::get('points/departments/{departmentId}/stats', [PointController::class, 'departmentStats']);

    // Badges
    Route::get('badges', [BadgeController::class, 'index']);
    Route::get('badges/{id}', [BadgeController::class, 'show']);
    Route::get('badges/users/{userId}', [BadgeController::class, 'userBadges']);
    Route::post('badges/users/{userId}/award', [BadgeController::class, 'awardBadge']);

    // Activities
    Route::apiResource('activities', ActivityController::class);
    Route::get('activities/upcoming/list', [ActivityController::class, 'upcoming']);
    Route::get('activities/past/list', [ActivityController::class, 'past']);
});
