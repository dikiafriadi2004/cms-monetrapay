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
 * Notification email to admin when a new contact form is submitted.
 * Reply-To is set to the submitter's email so admin can reply directly.
 */
class ContactNotification extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ContactSubmission $submission,
    ) {}

    public function envelope(): Envelope
    {
        $siteName = Setting::get('site_name', 'general', 'MonetraPay');

        $envelope = new Envelope(
            from: new Address(
                config('mail.from.address'),
                $siteName.' Bot',
            ),
            subject: '['.$siteName.'] Pendaftaran Baru: '.$this->submission->name,
        );

        // Set Reply-To to the submitter so admin can hit "Reply" in Gmail
        // and the response goes directly to them.
        if ($this->submission->email) {
            $envelope = new Envelope(
                from: $envelope->from,
                replyTo: [
                    new Address(
                        $this->submission->email,
                        $this->submission->name,
                    ),
                ],
                subject: $envelope->subject,
            );
        }

        return $envelope;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-notification',
        );
    }
}
