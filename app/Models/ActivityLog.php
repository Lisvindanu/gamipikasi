<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'task_id',
        'action',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related task
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Scope for filtering by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for filtering by task
     */
    public function scopeForTask($query, $taskId)
    {
        return $query->where('task_id', $taskId);
    }

    /**
     * Scope for filtering by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for recent activities
     */
    public function scopeRecent($query, $limit = 50)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Get icon for activity type
     */
    public function getIconAttribute(): string
    {
        return match($this->action) {
            'task_created' => 'plus-circle',
            'task_updated' => 'edit',
            'task_completed' => 'check-circle',
            'task_status_changed' => 'refresh-cw',
            'comment_added' => 'message-circle',
            'attachment_uploaded' => 'paperclip',
            'attachment_deleted' => 'trash-2',
            'task_assigned' => 'user-plus',
            'task_deleted' => 'x-circle',
            default => 'activity',
        };
    }

    /**
     * Get color for activity type
     */
    public function getColorAttribute(): string
    {
        return match($this->action) {
            'task_created' => 'var(--google-blue)',
            'task_completed' => 'var(--google-green)',
            'task_deleted' => 'var(--google-red)',
            'comment_added' => 'var(--google-yellow)',
            'attachment_uploaded' => 'var(--google-blue)',
            default => 'var(--text-secondary)',
        };
    }
}
