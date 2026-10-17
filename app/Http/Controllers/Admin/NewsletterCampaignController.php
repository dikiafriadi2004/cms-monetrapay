<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use App\Services\MailConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterCampaignController extends Controller
{
    public function index()
    {
        $campaigns = NewsletterCampaign::with('user')
            ->orderByDesc('created_at')
            ->paginate(15);

        $activeCount = NewsletterSubscriber::active()->count();

        return view('admin.newsletter.campaigns', compact('campaigns', 'activeCount'));
    }

    public function create()
    {
        $activeCount = NewsletterSubscriber::active()->count();

        return view('admin.newsletter.create-campaign', compact('activeCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:20'],
        ]);

        $campaign = NewsletterCampaign::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'status' => 'draft',
            'recipients_count' => NewsletterSubscriber::active()->count(),
        ]);

        return redirect()->route('admin.newsletter.campaigns.show', $campaign)
            ->with('success', 'Kampanye berhasil dibuat. Periksa dan kirim saat siap.');
    }

    public function show(NewsletterCampaign $campaign)
    {
        $activeCount = NewsletterSubscriber::active()->count();

        return view('admin.newsletter.show-campaign', compact('campaign', 'activeCount'));
    }

    public function send(NewsletterCampaign $campaign)
    {
        if ($campaign->isSent()) {
            return back()->with('error', 'Kampanye ini sudah pernah dikirim.');
        }

        $subscribers = NewsletterSubscriber::active()->get();

        if ($subscribers->isEmpty()) {
            return back()->with('error', 'Tidak ada subscriber aktif untuk menerima newsletter.');
        }

        // Apply latest mail config
        app(MailConfigService::class)->apply();

        $campaign->update([
            'status' => 'sending',
            'recipients_count' => $subscribers->count(),
        ]);

        $siteName = Setting::get('site_name', 'general', 'MonetraPay');
        $sentCount = 0;
        $failed = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::send(
                    'emails.newsletter-blast',
                    ['campaign' => $campaign, 'subscriber' => $subscriber, 'siteName' => $siteName],
                    function ($mail) use ($subscriber, $campaign, $siteName) {
                        $unsubUrl = url(route('newsletter.unsubscribe', $subscriber->confirmation_token ?? $subscriber->id, false));
                        $mail->to($subscriber->email, $subscriber->name ?? '')
                            ->subject($campaign->subject)
                            ->from(
                                config('mail.from.address'),
                                $siteName
                            );
                    }
                );
                $sentCount++;
            } catch (\Throwable $e) {
                $failed++;
                report($e);
            }
        }

        $campaign->update([
            'status' => $failed > 0 && $sentCount === 0 ? 'failed' : 'sent',
            'sent_count' => $sentCount,
            'sent_at' => now(),
        ]);

        AuditLog::record('newsletter.sent', [
            'campaign_id' => $campaign->id,
            'sent' => $sentCount,
            'failed' => $failed,
        ]);

        return back()->with('success', "Newsletter berhasil dikirim ke {$sentCount} subscriber.".($failed > 0 ? " {$failed} gagal." : ''));
    }

    public function destroy(NewsletterCampaign $campaign)
    {
        if ($campaign->status === 'sending') {
            return back()->with('error', 'Tidak bisa menghapus kampanye yang sedang dikirim.');
        }
        $campaign->delete();

        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Kampanye berhasil dihapus.');
    }
}
