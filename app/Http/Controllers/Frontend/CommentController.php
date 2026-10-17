<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class CommentController extends Controller
{
    /**
     * Submit a comment for a blog post.
     */
    public function store(Request $request, Blog $blog)
    {
        // Only allow comments on published blogs
        if ($blog->status !== 'published') {
            abort(404);
        }

        // Honeypot
        if (filled($request->input('website'))) {
            return back()->with('comment_error', 'Komentar gagal terkirim.');
        }

        // Time check
        $rendered = (int) $request->input('_t', 0);
        if ($rendered === 0 || (time() - $rendered) < 3) {
            return back()->with('comment_error', 'Komentar gagal terkirim.');
        }

        // Rate limit
        $key = 'comment:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->with('comment_error', 'Terlalu banyak komentar. Coba lagi nanti.');
        }

        $validated = $request->validate([
            'author_name' => ['required', 'string', 'min:2', 'max:100'],
            'author_email' => ['required', 'email:rfc', 'max:255'],
            'author_website' => ['nullable', 'url', 'max:255'],
            'content' => ['required', 'string', 'min:5', 'max:2000'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
        ], [
            'author_name.required' => 'Nama wajib diisi.',
            'author_email.required' => 'Email wajib diisi.',
            'author_email.email' => 'Format email tidak valid.',
            'content.required' => 'Komentar tidak boleh kosong.',
            'content.min' => 'Komentar terlalu pendek.',
        ]);

        // If replying, parent must belong to same blog
        if (! empty($validated['parent_id'])) {
            $parent = Comment::find($validated['parent_id']);
            if (! $parent || $parent->blog_id !== $blog->id) {
                abort(403);
            }
        }

        // Duplicate detection — same blog + email + content posted in last 10 minutes
        $duplicate = Comment::where('blog_id', $blog->id)
            ->where('author_email', $validated['author_email'])
            ->where('content', $validated['content'])
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();

        if ($duplicate) {
            return back()
                ->with('comment_error', 'Komentar yang sama sudah pernah Anda kirim baru saja.')
                ->withFragment('comments');
        }

        // Spam heuristic: more than 2 links in content → mark as spam (hidden)
        // Otherwise: auto-approve so the comment appears immediately.
        $linkCount = preg_match_all('/https?:\/\//i', $validated['content']);
        $status = $linkCount > 2 ? Comment::STATUS_SPAM : Comment::STATUS_APPROVED;

        RateLimiter::hit($key, 600);

        $comment = Comment::create(array_merge($validated, [
            'blog_id' => $blog->id,
            'status' => $status,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]));

        // Notify admins of every new comment (for visibility, even auto-approved)
        try {
            $admins = User::role('Super Admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewCommentNotification($comment));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return back()
            ->with('comment_success', $status === Comment::STATUS_APPROVED
                ? 'Komentar Anda berhasil dikirim.'
                : 'Komentar diterima dan akan diperiksa.')
            ->withFragment('comments');
    }
}
