<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['blog:id,title,slug', 'parent:id,author_name'])->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('author_name', 'like', "%{$term}%")
                    ->orWhere('author_email', 'like', "%{$term}%")
                    ->orWhere('content', 'like', "%{$term}%");
            });
        }

        $comments = $query->paginate(20)->withQueryString();
        $counts = [
            'pending' => Comment::pending()->count(),
            'approved' => Comment::approved()->count(),
            'spam' => Comment::where('status', Comment::STATUS_SPAM)->count(),
            'trash' => Comment::where('status', Comment::STATUS_TRASH)->count(),
        ];

        return view('admin.comments.index', compact('comments', 'counts'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['status' => Comment::STATUS_APPROVED]);

        return back()->with('success', 'Komentar disetujui.');
    }

    public function markSpam(Comment $comment)
    {
        $comment->update(['status' => Comment::STATUS_SPAM]);

        return back()->with('success', 'Komentar ditandai spam.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Komentar dihapus.');
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:approve,spam,delete'],
            'ids' => ['required', 'array', 'min:1', 'max:100'],
            'ids.*' => ['integer'],
        ]);

        $ids = $validated['ids'];
        $msg = 'Tidak ada komentar yang diproses.';

        switch ($validated['action']) {
            case 'approve':
                $count = Comment::whereIn('id', $ids)->update(['status' => Comment::STATUS_APPROVED]);
                $msg = "{$count} komentar disetujui.";
                break;
            case 'spam':
                $count = Comment::whereIn('id', $ids)->update(['status' => Comment::STATUS_SPAM]);
                $msg = "{$count} komentar ditandai spam.";
                break;
            case 'delete':
                $count = Comment::whereIn('id', $ids)->delete();
                $msg = "{$count} komentar dihapus.";
                break;
        }

        return back()->with('success', $msg);
    }
}
