<?php

namespace App\Jobs;

use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletterToSubscriber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected NewsletterSubscriber $subscriber,
        protected NewsletterCampaign $campaign,
        protected string $siteName,
    ) {}

    public function handle(): void
    {
        Mail::send(
            'emails.newsletter-blast',
            ['campaign' => $this->campaign, 'subscriber' => $this->subscriber, 'siteName' => $this->siteName],
            function ($mail) {
                $unsubUrl = url(route('newsletter.unsubscribe', $this->subscriber->confirmation_token, false));
                $mail->to($this->subscriber->email, $this->subscriber->name ?? '')
                    ->subject($this->campaign->subject)
                    ->from(
                        config('mail.from.address'),
                        $this->siteName
                    );
            }
        );
    }
}
