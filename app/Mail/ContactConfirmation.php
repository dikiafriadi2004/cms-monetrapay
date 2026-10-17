<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Auto-reply email sent to the person who submitted the contact form.
 * Confirms we received the message; sets up reply chain to admin.
 */
class ContactConfirmation extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ContactSubmission $submission,
    ) {}

    public function envelope(): Envelope
    {
        $siteName = Setting::get('site_name', 'general', 'MonetraPay');
        $adminEmail = Setting::get('contact_email', 'general')
            ?? config('mail.from.address');

        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                $siteName,
            ),
            replyTo: $adminEmail ? [
                new Address($adminEmail, $siteName.' Support'),
            ] : [],
            subject: 'Terima kasih atas pendaftaran Anda - '.$siteName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-confirmation',
        );
    }
}
