<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'title',
        'content',
        'slug',
        'visibility',
        'category',
        'is_pinned',
        'published_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from title when creating
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);

                // Ensure unique slug
                $count = 1;
                while (static::where('slug', $post->slug)->exists()) {
                    $post->slug = Str::slug($post->title) . '-' . $count++;
                }
            }
        });

        // Update slug when title changes
        static::updating(function ($post) {
            if ($post->isDirty('title')) {
                $newSlug = Str::slug($post->title);

                // Ensure unique slug (exclude current post)
                $count = 1;
                while (static::where('slug', $newSlug)->where('id', '!=', $post->id)->exists()) {
                    $newSlug = Str::slug($post->title) . '-' . $count++;
                }

                $post->slug = $newSlug;
            }
        });
    }

    /**
     * Get the author of the post
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the department this post belongs to
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get all comments for this post
     */
    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    /**
     * Get all attachments for this post
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(PostAttachment::class);
    }

    /**
     * Scope to get only published posts
     */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now())
                    ->orWhereNull('published_at');
    }

    /**
     * Scope to get only public posts
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Scope to get pinned posts
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Check if user can edit this post
     */
    public function canEdit(User $user): bool
    {
        // Author can edit their own posts
        if ($this->user_id === $user->id) {
            return true;
        }

        // Lead and Co-Lead can edit any post
        if (in_array($user->role, ['lead', 'co-lead'])) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can delete this post
     */
    public function canDelete(User $user): bool
    {
        return $this->canEdit($user);
    }
}
