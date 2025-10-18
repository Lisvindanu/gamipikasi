<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\HRController;
use App\Http\Controllers\Dashboard\HeadController;
use App\Http\Controllers\Dashboard\LeadController;
use App\Http\Controllers\Dashboard\MemberController;
use App\Http\Controllers\Public\LeaderboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Pages (No Auth Required)
Route::get('/', [LeaderboardController::class, 'home'])->name('home');
Route::get('/leaderboard', [LeaderboardController::class, 'leaderboard'])->name('public.leaderboard');
Route::get('/rules', [LeaderboardController::class, 'rules'])->name('public.rules');
Route::get('/badges', [LeaderboardController::class, 'badges'])->name('public.badges');
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

// Profile Edit (Auth Required)
Route::middleware('auth')->group(function () {
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Posts Routes
Route::prefix('posts')->name('posts.')->group(function () {
    // Public routes (anyone can view)
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/view/{slug}', [PostController::class, 'show'])->name('show');
    Route::get('/attachments/{attachment}/download', [PostController::class, 'downloadAttachment'])->name('attachments.download');

    // Protected routes (authenticated users only)
    Route::middleware('auth')->group(function () {
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');

        // Comments
        Route::post('/{post}/comments', [PostController::class, 'addComment'])->name('comments.store');
        Route::delete('/comments/{comment}', [PostController::class, 'deleteComment'])->name('comments.destroy');

        // Attachments
        Route::delete('/attachments/{attachment}', [PostController::class, 'deleteAttachment'])->name('attachments.destroy');
    });
});

// Protected Routes
Route::middleware('auth')->group(function () {

    // Notifications (accessible by all authenticated users)
    Route::get('/notifications', [MemberController::class, 'getNotifications'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [MemberController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [MemberController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');

    // Task Attachment View/Download (accessible by all authenticated users)
    Route::get('/task-attachments/{attachment}', [MemberController::class, 'viewAttachment'])->name('tasks.attachments.view');
    Route::get('/task-attachments/{attachment}/download', [MemberController::class, 'downloadAttachment'])->name('tasks.attachments.download');

    // HR Dashboard (for HR Head and HR Members only)
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::get('/dashboard', [HRController::class, 'index'])->name('dashboard');
        Route::post('/points', [HRController::class, 'addPoint'])->name('points.add');
        Route::post('/points/bulk', [HRController::class, 'bulkAddPoints'])->name('points.bulk');
    });

    // Head of Department Dashboard (for Head only)
    Route::prefix('head')->name('head.')->middleware('role:head')->group(function () {
        Route::get('/dashboard', [HeadController::class, 'index'])->name('dashboard');

        // Task Management (Trello Board)
        Route::get('/tasks/board', [HeadController::class, 'taskBoard'])->name('tasks.board');
        Route::post('/tasks', [HeadController::class, 'createTask'])->name('tasks.create');
        Route::patch('/tasks/{task}/status', [HeadController::class, 'updateTaskStatus'])->name('tasks.update-status');
        Route::delete('/tasks/{task}', [HeadController::class, 'deleteTask'])->name('tasks.delete');

        // Task attachments & comments (for tasks assigned to head)
        Route::post('/tasks/{task}/attachments', [HeadController::class, 'uploadTaskAttachment'])->name('tasks.attachments.upload');
        Route::delete('/task-attachments/{attachment}', [HeadController::class, 'deleteTaskAttachment'])->name('tasks.attachments.delete');
        Route::post('/tasks/{task}/comments', [HeadController::class, 'addTaskComment'])->name('tasks.comments.add');
    });

    // Leadership Dashboard (for Lead, Co-Lead)
    Route::prefix('lead')->name('lead.')->middleware('role:lead,co-lead')->group(function () {
        Route::get('/dashboard', [LeadController::class, 'index'])->name('dashboard');
        Route::get('/activity-log', [LeadController::class, 'activityLog'])->name('activity-log');
        Route::post('/export/points', [LeadController::class, 'exportPoints'])->name('export.points');
        Route::post('/export/members', [LeadController::class, 'exportMembers'])->name('export.members');

        // Task Management (Trello Board)
        Route::get('/tasks/board', [LeadController::class, 'taskBoard'])->name('tasks.board');
        Route::post('/tasks', [LeadController::class, 'createTask'])->name('tasks.create');
        Route::patch('/tasks/{task}/status', [LeadController::class, 'updateTaskStatus'])->name('tasks.update-status');
        Route::delete('/tasks/{task}', [LeadController::class, 'deleteTask'])->name('tasks.delete');
    });

    // Settings & User Management (for Lead, Co-Lead, and HR Head)
    Route::middleware('can.manage.users')->group(function () {
        Route::get('/settings', [LeadController::class, 'settings'])->name('lead.settings');
        Route::get('/settings/users', [LeadController::class, 'manageUsers'])->name('lead.users.index');
        Route::patch('/settings/users/{user}/role', [LeadController::class, 'updateUserRole'])->name('lead.users.update-role');
        Route::patch('/settings/users/{user}/department', [LeadController::class, 'updateUserDepartment'])->name('lead.users.update-department');
    });

    // Member Dashboard (for all members)
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', [MemberController::class, 'index'])->name('dashboard');

        // Task Board
        Route::get('/tasks/board', [MemberController::class, 'taskBoard'])->name('tasks.board');

        // Task updates
        Route::patch('/tasks/{task}/status', [MemberController::class, 'updateTaskStatus'])->name('tasks.update-status');

        // Task attachments (evidence/proof uploads)
        Route::post('/tasks/{task}/attachments', [MemberController::class, 'uploadTaskAttachment'])->name('tasks.attachments.upload');
        Route::delete('/task-attachments/{attachment}', [MemberController::class, 'deleteTaskAttachment'])->name('tasks.attachments.delete');

        // Task comments
        Route::post('/tasks/{task}/comments', [MemberController::class, 'addTaskComment'])->name('tasks.comments.add');
    });
});
