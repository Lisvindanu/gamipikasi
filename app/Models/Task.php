<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'department_id',
        'assigned_by',
        'assigned_to',
        'status',
        'priority',
        'deadline',
        'point_reward',
        'completion_note',
        'completed_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'deadline' => 'date',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
            ->whereIn('status', ['pending', 'in_progress']);
    }

    // Helper methods
    public function isOverdue(): bool
    {
        return $this->deadline &&
               $this->deadline->isPast() &&
               in_array($this->status, ['pending', 'in_progress']);
    }

    public function canBeCompletedBy(User $user): bool
    {
        return $this->assigned_to === $user->id &&
               in_array($this->status, ['pending', 'in_progress']);
    }

    public function canBeEditedBy(User $user): bool
    {
        return $this->assigned_by === $user->id &&
               in_array($this->status, ['pending', 'in_progress']);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }
}
