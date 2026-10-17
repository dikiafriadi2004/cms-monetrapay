<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use DateTimeInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::orderByDesc('created_at');

        if ($request->filled('status')) {
            match ($request->status) {
                'active' => $query->whereNotNull('confirmed_at')->whereNull('unsubscribed_at'),
                'pending' => $query->whereNull('confirmed_at')->whereNull('unsubscribed_at'),
                'unsubscribed' => $query->whereNotNull('unsubscribed_at'),
                default => null,
            };
        }

        if ($request->filled('search')) {
            $query->where('email', 'like', '%'.$request->input('search').'%');
        }

        $subscribers = $query->paginate(30)->withQueryString();
        $counts = [
            'active' => NewsletterSubscriber::active()->count(),
            'pending' => NewsletterSubscriber::whereNull('confirmed_at')->whereNull('unsubscribed_at')->count(),
            'unsubscribed' => NewsletterSubscriber::whereNotNull('unsubscribed_at')->count(),
            'total' => NewsletterSubscriber::count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'counts'));
    }

    public function export(): StreamedResponse
    {
        $filename = 'newsletter-subscribers-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Email', 'Name', 'Status', 'Confirmed At', 'Subscribed At']);

            NewsletterSubscriber::orderBy('email')->chunk(500, function ($chunk) use ($out) {
                foreach ($chunk as $sub) {
                    $status = $sub->isUnsubscribed() ? 'unsubscribed' : ($sub->isConfirmed() ? 'active' : 'pending');
                    fputcsv($out, [
                        $sub->email,
                        $sub->name,
                        $status,
                        $sub->confirmed_at instanceof DateTimeInterface ? $sub->confirmed_at->format('Y-m-d H:i:s') : $sub->confirmed_at,
                        $sub->created_at instanceof DateTimeInterface ? $sub->created_at->format('Y-m-d H:i:s') : $sub->created_at,
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber dihapus.');
    }
}
