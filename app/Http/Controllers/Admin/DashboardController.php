<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ContactSubmission;
use App\Models\Media;
use App\Models\NewsletterSubscriber;
use App\Models\Page;
use App\Models\User;
use App\Services\AnalyticsService;

class DashboardController extends Controller
{
    public function __construct(
        protected AnalyticsService $analytics,
    ) {}

    public function index()
    {
        // Content counters
        $stats = [
            'blogs_total' => Blog::count(),
            'blogs_published' => Blog::where('status', 'published')->count(),
            'blogs_draft' => Blog::where('status', 'draft')->count(),
            'pages_total' => Page::count(),
            'pages_published' => Page::where('status', 'published')->count(),
            'categories' => Category::count(),
            'users' => User::count(),
            'newsletter_active' => NewsletterSubscriber::active()->count(),
            'comments_pending' => Comment::pending()->count(),
            'media_total' => Media::count(),
            'old_drafts' => Blog::where('status', 'draft')->where('created_at', '<', now()->subDays(30))->count(),
        ];

        // Contact stats/PII are only exposed to users with contact.view
        if (auth()->user()->can('contact.view')) {
            $stats['contacts_unread'] = ContactSubmission::where('is_read', false)->count();
            $stats['contacts_total'] = ContactSubmission::count();
        } else {
            $stats['contacts_unread'] = null;
            $stats['contacts_total'] = null;
        }

        // Recent activity
        $recentBlogs = Blog::with('author')->latest()->limit(5)->get(['id', 'title', 'status', 'created_at', 'user_id']);

        // Recent contacts — only for users with contact.view permission
        $recentContacts = collect();
        if (auth()->user()->can('contact.view')) {
            $recentContacts = ContactSubmission::latest()->limit(5)->get(['id', 'name', 'phone', 'is_read', 'created_at']);
        }

        // Posts published this month
        $postsThisMonth = Blog::where('status', 'published')
            ->whereMonth('published_at', now()->month)
            ->whereYear('published_at', now()->year)
            ->count();

        // Contacts this month — only for users with contact.view permission
        $contactsThisMonth = auth()->user()->can('contact.view')
            ? ContactSubmission::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
            : null;

        // Monthly blog post trend (last 6 months)
        $monthlyTrend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyTrend->push([
                'label' => $month->translatedFormat('M Y'),
                'short' => $month->translatedFormat('M'),
                'count' => Blog::where('status', 'published')
                    ->whereMonth('published_at', $month->month)
                    ->whereYear('published_at', $month->year)
                    ->count(),
            ]);
        }

        // Top 5 most recent published blogs (for quick stats)
        $topBlogs = Blog::where('status', 'published')
            ->with('author')
            ->orderByDesc('published_at')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'published_at', 'user_id']);

        // Analytics summary (optional)
        $analyticsSummary = null;
        $analyticsTrend = null;
        try {
            if ($this->analytics->isConfigured()) {
                $analyticsSummary = $this->analytics->getSummary();
                $analyticsTrend = $this->analytics->getDailyTrend();
            }
        } catch (\Throwable $e) {
            // silent fail
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentBlogs',
            'recentContacts',
            'postsThisMonth',
            'contactsThisMonth',
            'monthlyTrend',
            'topBlogs',
            'analyticsSummary',
            'analyticsTrend',
        ));
    }
}
