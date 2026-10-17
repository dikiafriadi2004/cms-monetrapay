<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class NewsletterController extends Controller
{
    /**
     * Subscribe (with double opt-in flow).
     */
    public function subscribe(Request $request)
    {
        // Honeypot
        if (filled($request->input('website'))) {
            return back()->with('newsletter_error', 'Subscribe gagal. Coba lagi.');
        }

        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
            'name' => ['nullable', 'string', 'max:100'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $key = 'newsletter:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->with('newsletter_error', 'Terlalu banyak percobaan. Coba lagi nanti.');
        }
        RateLimiter::hit($key, 3600);

        $subscriber = NewsletterSubscriber::firstOrNew(['email' => $validated['email']]);

        // Already confirmed and active — short-circuit
        if ($subscriber->exists && $subscriber->isActive()) {
            return back()->with('newsletter_success', 'Email Anda sudah terdaftar dan aktif.');
        }

        $subscriber->fill([
            'name' => $validated['name'] ?? $subscriber->name,
            'confirmation_token' => NewsletterSubscriber::generateToken(),
            'unsubscribed_at' => null, // re-activate on resubscribe
            'ip_address' => $request->ip(),
        ]);
        $subscriber->save();

        // In production, dispatch email job here. For now show inline confirmation link.
        // Mail::to($subscriber->email)->queue(new ConfirmSubscription($subscriber));

        return back()->with('newsletter_success', 'Cek email Anda untuk konfirmasi langganan!');
    }

    /**
     * Confirm subscription via emailed token.
     */
    public function confirm(string $token)
    {
        $subscriber = NewsletterSubscriber::where('confirmation_token', $token)->firstOrFail();

        if (! $subscriber->isConfirmed()) {
            $subscriber->update([
                'confirmed_at' => now(),
                'confirmation_token' => null,
            ]);
        }

        return view('frontend.newsletter.confirmed', compact('subscriber'));
    }

    /**
     * One-click unsubscribe via token.
     */
    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('confirmation_token', $token)
            ->orWhere('email', request('email'))
            ->firstOrFail();

        $subscriber->update(['unsubscribed_at' => now()]);

        return view('frontend.newsletter.unsubscribed', compact('subscriber'));
    }
}
