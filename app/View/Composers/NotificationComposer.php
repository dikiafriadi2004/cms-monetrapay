<?php

namespace App\View\Composers;

use App\Models\Comment;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Only run if user is authenticated
        if (!auth()->check()) {
            $view->with('notifications', [
                'pending_comments' => 0,
                'unread_contacts' => 0,
                'total' => 0,
            ]);
            return;
        }

        // Cache notifications per user for 5 minutes
        $notifications = Cache::remember(
            'notifications.user.' . auth()->id(),
            300, // 5 minutes
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

        $view->with('notifications', $notifications);
    }
}
