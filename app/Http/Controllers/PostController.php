<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\PostComment;
use App\Models\Department;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display a listing of posts
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $category = $request->get('category');
        $department = $request->get('department');

        $posts = $this->postService->getPostsForUser($user, $category, $department, $search);
        $departments = Department::all();

        return view('posts.index', compact('posts', 'departments', 'search', 'category', 'department'));
    }

    /**
     * Show the form for creating a new post
     */
    public function create()
    {
        $user = Auth::user();

        if (!$this->postService->canCreatePost($user)) {
            abort(403, 'Anda tidak memiliki izin untuk membuat postingan');
        }

        $departments = Department::all();
        $allowedCategories = $this->postService->getAllowedCategories($user);

        return view('posts.create', compact('departments', 'allowedCategories'));
    }

    /**
     * Store a newly created post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'visibility' => 'required|in:public,internal',
            'category' => 'required|in:announcement,report,event,regulation,minutes,general',
            'is_pinned' => 'nullable|boolean',
            'attachments.*' => 'nullable|file|max:10240', // Max 10MB per file
        ]);

        try {
            $attachments = $request->hasFile('attachments') ? $request->file('attachments') : [];

            $post = $this->postService->createPost(
                Auth::user(),
                $validated,
                $attachments
            );

            return redirect()->route('posts.show', $post->slug)
                ->with('success', 'Postingan berhasil dibuat!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified post
     */
    public function show(string $slug)
    {
        $post = $this->postService->getPostBySlug($slug);
        $user = Auth::user();

        // Check visibility
        if ($post->visibility === 'internal' && !$user) {
            abort(403, 'Postingan ini hanya dapat dilihat oleh anggota GDGoC');
        }

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post
     */
    public function edit(Post $post)
    {
        if (!$post->canEdit(Auth::user())) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit postingan ini');
        }

        $departments = Department::all();
        $allowedCategories = $this->postService->getAllowedCategories(Auth::user());

        return view('posts.edit', compact('post', 'departments', 'allowedCategories'));
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post)
    {
        // Check permission first
        if (!$post->canEdit(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengedit postingan ini');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'visibility' => 'required|in:public,internal',
            'category' => 'required|in:announcement,report,event,regulation,minutes,general',
            'is_pinned' => 'nullable|boolean',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        // Debug log
        \Log::info('Post Update Data:', [
            'post_id' => $post->id,
            'old_visibility' => $post->visibility,
            'new_visibility' => $validated['visibility'],
            'old_category' => $post->category,
            'new_category' => $validated['category'],
            'validated_data' => $validated
        ]);

        try {
            $attachments = $request->hasFile('attachments') ? $request->file('attachments') : [];

            $updatedPost = $this->postService->updatePost(
                $post,
                Auth::user(),
                $validated,
                $attachments
            );

            \Log::info('Post Updated Successfully:', [
                'post_id' => $updatedPost->id,
                'final_visibility' => $updatedPost->visibility,
                'final_category' => $updatedPost->category,
            ]);

            // Clear session old input
            session()->forget('_old_input');

            return redirect()->route('posts.show', $updatedPost->slug)
                ->with('success', 'Postingan berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Post update failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui postingan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post)
    {
        try {
            $this->postService->deletePost($post, Auth::user());

            return redirect()->route('posts.index')
                ->with('success', 'Postingan berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Add comment to post
     */
    public function addComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        try {
            $this->postService->addComment($post, Auth::user(), $validated['comment']);

            return back()->with('success', 'Komentar berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete comment
     */
    public function deleteComment(PostComment $comment)
    {
        try {
            $this->postService->deleteComment($comment, Auth::user());

            return back()->with('success', 'Komentar berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete attachment
     */
    public function deleteAttachment(PostAttachment $attachment)
    {
        try {
            $this->postService->deleteAttachment($attachment, Auth::user());

            // Return JSON for AJAX requests
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lampiran berhasil dihapus!'
                ]);
            }

            return back()->with('success', 'Lampiran berhasil dihapus!');
        } catch (\Exception $e) {
            // Return JSON error for AJAX requests
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(PostAttachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download(
            $attachment->file_path,
            $attachment->file_name
        );
    }
}
