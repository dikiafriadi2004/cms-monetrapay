<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactConfirmation;
use App\Mail\ContactNotification;
use App\Models\ContactSubmission;
use App\Models\Setting;
use App\Services\MailConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    /**
     * Handle contact form submission.
     * Protected by:
     * - Honeypot field (must be empty)
     * - Rate limiting (3 submissions per IP per hour)
     * - Time check (form must take >= 3 seconds to fill)
     */
    public function submit(Request $request)
    {
        // Honeypot — bots fill all fields including hidden ones
        if (filled($request->input('website'))) {
            return back()->with('contact_error', 'Pendaftaran gagal. Silakan coba lagi.');
        }

        // Time check — form rendered timestamp must exist and be at least 3s ago
        $rendered = (int) $request->input('_t', 0);
        if ($rendered === 0 || (time() - $rendered) < 3) {
            return back()->with('contact_error', 'Pendaftaran gagal. Silakan coba lagi.');
        }

        // Rate limit per IP: 3 submissions per hour
        $key = 'contact:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            return back()
                ->with('contact_error', "Terlalu banyak percobaan. Coba lagi dalam {$minutes} menit.")
                ->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 2 karakter.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp hanya boleh berisi angka, +, -, spasi, dan tanda kurung.',
            'phone.min' => 'Nomor WhatsApp minimal 8 digit.',
            'email.email' => 'Format email tidak valid.',
            'message.max' => 'Pesan maksimal 1000 karakter.',
        ]);

        RateLimiter::hit($key, 3600);

        $submission = ContactSubmission::create(array_merge($validated, [
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'is_read' => false,
        ]));

        // Auto-reply to submitter (if email provided) + notify admin
        try {
            // Re-apply mail config from DB to override any cached .env values
            app(MailConfigService::class)->apply();

            // 1. Send confirmation auto-reply to the submitter
            if (! empty($submission->email)) {
                Mail::to($submission->email)
                    ->send(new ContactConfirmation($submission));
            }

            // 2. Notify admin email (with Reply-To = submitter)
            $adminEmail = Setting::get('contact_email', 'general')
                ?? config('mail.from.address');

            if ($adminEmail) {
                Mail::to($adminEmail)
                    ->send(new ContactNotification($submission));
            }
        } catch (\Throwable $e) {
            // Mail failure shouldn't break the user flow
            report($e);
        }

        return back()
            ->with('contact_success', 'Terima kasih! Pendaftaran Anda telah diterima. Tim kami akan segera menghubungi Anda.');
    }
}
