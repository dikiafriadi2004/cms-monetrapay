<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\ContactSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    /**
     * Get current user's notifications count.
     */
    public function index(): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json([
                'pending_comments' => 0,
                'unread_contacts' => 0,
                'total' => 0,
            ]);
        }

        // Cache for 2 minutes (shorter for real-time feel)
        $notifications = Cache::remember(
            'notifications.user.' . auth()->id(),
            120,
            function () {
                $pendingComments = Comment::where('status', 'pending')->count();
                $unreadContacts = ContactSubmission::where('is_read', false)->count();

                return [
                    'pending_comments' => $pendingComments,
                    'unread_contacts' => $unreadContacts,
                    'total' => $pendingComments + $unreadContacts,
                ];
            }
        );

        return response()->json($notifications);
    }
}
