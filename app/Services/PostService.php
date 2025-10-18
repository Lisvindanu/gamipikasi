<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostAttachment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostService
{
    /**
     * Get posts with filters
     */
    public function getPosts(?string $visibility = null, ?string $category = null, ?int $departmentId = null, int $perPage = 10)
    {
        $query = Post::with(['author:id,name,role,department_id', 'department:id,name', 'comments'])
            ->published()
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc');

        if ($visibility) {
            $query->where('visibility', $visibility);
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get single post by slug
     */
    public function getPostBySlug(string $slug): ?Post
    {
        return Post::with(['author', 'department', 'comments.user', 'attachments'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Create a new post
     */
    public function createPost(User $author, array $data, array $attachments = []): Post
    {
        return DB::transaction(function () use ($author, $data, $attachments) {
            // Check if user can create posts
            if (!$this->canCreatePost($author)) {
                throw new \Exception('You do not have permission to create posts');
            }

            // Create post
            $post = Post::create([
                'user_id' => $author->id,
                'department_id' => $data['department_id'] ?? null,
                'title' => $data['title'],
                'content' => $data['content'],
                'visibility' => $data['visibility'] ?? 'internal',
                'category' => $data['category'] ?? 'general',
                'is_pinned' => $data['is_pinned'] ?? false,
                'published_at' => $data['published_at'] ?? now(),
            ]);

            // Handle attachments
            if (!empty($attachments)) {
                $this->handleAttachments($post, $attachments);
            }

            return $post->fresh(['author', 'department', 'attachments']);
        });
    }

    /**
     * Update a post
     */
    public function updatePost(Post $post, User $user, array $data, array $newAttachments = []): Post
    {
        return DB::transaction(function () use ($post, $user, $data, $newAttachments) {
            // Check permission
            if (!$post->canEdit($user)) {
                throw new \Exception('You do not have permission to edit this post');
            }

            // Update post
            $post->update([
                'title' => $data['title'],
                'content' => $data['content'],
                'department_id' => $data['department_id'] ?? null,
                'visibility' => $data['visibility'],
                'category' => $data['category'],
                'is_pinned' => isset($data['is_pinned']) ? (bool)$data['is_pinned'] : false,
                'published_at' => $data['published_at'] ?? $post->published_at,
            ]);

            // Handle new attachments
            if (!empty($newAttachments)) {
                $this->handleAttachments($post, $newAttachments);
            }

            return $post->fresh(['author', 'department', 'attachments']);
        });
    }

    /**
     * Delete a post
     */
    public function deletePost(Post $post, User $user): bool
    {
        if (!$post->canDelete($user)) {
            throw new \Exception('You do not have permission to delete this post');
        }

        // Attachments will be deleted automatically via model event
        return $post->delete();
    }

    /**
     * Add comment to a post
     */
    public function addComment(Post $post, User $user, string $comment): PostComment
    {
        return PostComment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'comment' => $comment,
        ]);
    }

    /**
     * Delete a comment
     */
    public function deleteComment(PostComment $comment, User $user): bool
    {
        if (!$comment->canDelete($user)) {
            throw new \Exception('You do not have permission to delete this comment');
        }

        return $comment->delete();
    }

    /**
     * Delete an attachment
     */
    public function deleteAttachment(PostAttachment $attachment, User $user): bool
    {
        // Only post author or admins can delete attachments
        if (!$attachment->post->canEdit($user)) {
            throw new \Exception('You do not have permission to delete this attachment');
        }

        return $attachment->delete();
    }

    /**
     * Handle file attachments
     */
    protected function handleAttachments(Post $post, array $files): void
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $this->storeAttachment($post, $file);
            }
        }
    }

    /**
     * Store a single attachment
     */
    protected function storeAttachment(Post $post, UploadedFile $file): PostAttachment
    {
        // Store file
        $path = $file->store('post-attachments', 'public');

        // Create attachment record
        return PostAttachment::create([
            'post_id' => $post->id,
            'uploaded_by' => $post->user_id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);
    }

    /**
     * Check if user can create posts
     */
    public function canCreatePost(User $user): bool
    {
        // Lead, Co-Lead, HR can post anything
        if (in_array($user->role, ['lead', 'co-lead']) || $user->department_id == 1) {
            return true;
        }

        // Secretary, Bendahara can post
        if (in_array($user->role, ['secretary', 'bendahara'])) {
            return true;
        }

        // Heads can post
        if ($user->role === 'head') {
            return true;
        }

        return false;
    }

    /**
     * Get allowed categories for user based on department
     */
    public function getAllowedCategories(User $user): array
    {
        // Lead, Co-Lead, HR can post all categories
        if (in_array($user->role, ['lead', 'co-lead']) || $user->department_id == 1) {
            return ['announcement', 'event', 'report', 'regulation', 'minutes', 'general'];
        }

        // Secretary can only post minutes and announcements
        if ($user->role === 'secretary') {
            return ['minutes', 'announcement'];
        }

        // Bendahara can only post reports
        if ($user->role === 'bendahara') {
            return ['report'];
        }

        // Department-based categories
        if ($user->department_id) {
            switch ($user->department_id) {
                case 2: // Media Creative
                    return ['general'];
                case 3: // Event
                    return ['event'];
                case 4: // Public Relationship
                    return ['announcement'];
                case 5: // Curriculum Web
                case 6: // Curriculum IoT
                case 7: // Curriculum ML
                case 8: // Curriculum Game
                    return ['general'];
                default:
                    return ['general'];
            }
        }

        return ['general'];
    }

    /**
     * Get posts accessible by user
     */
    public function getPostsForUser(User $user = null, ?string $category = null, ?int $departmentId = null, ?string $search = null, int $perPage = 10)
    {
        $query = Post::with(['author:id,name,role', 'department:id,name', 'comments'])
            ->published()
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc');

        // If user is authenticated (core team member), show both public and internal
        // If not authenticated or not a member, show only public
        if (!$user || !in_array($user->role, ['lead', 'co-lead', 'head', 'member', 'secretary', 'bendahara'])) {
            $query->where('visibility', 'public');
        }

        // Filter by category
        if ($category) {
            $query->where('category', $category);
        }

        // Filter by department
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        // Search in title and content
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        return $query->paginate($perPage)->appends([
            'search' => $search,
            'category' => $category,
            'department' => $departmentId,
        ]);
    }
}
