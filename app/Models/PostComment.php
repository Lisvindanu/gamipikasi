<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostComment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'comment',
    ];

    /**
     * Get the post this comment belongs to
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user who wrote this comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user can delete this comment
     */
    public function canDelete(User $user): bool
    {
        // Author can delete their own comment
        if ($this->user_id === $user->id) {
            return true;
        }

        // Post author can delete comments on their post
        if ($this->post->user_id === $user->id) {
            return true;
        }

        // Lead and Co-Lead can delete any comment
        if (in_array($user->role, ['lead', 'co-lead'])) {
            return true;
        }

        return false;
    }
}
