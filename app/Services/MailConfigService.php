<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

/**
 * Apply mail settings from the `email` settings group to runtime config.
 * Called early in boot AND after settings update so Mail facade uses DB config.
 *
 * Critical: After Config::set(), we must also purge the Mail manager's
 * resolved transport/mailer instances so they rebuild with the new config.
 */
class MailConfigService
{
    /**
     * Apply settings to config('mail.*') and purge resolved mail instances.
     */
    public function apply(): void
    {
        try {
            // Read directly from DB — bypass cache to always get latest values
            $rows = \DB::table('settings')->where('group', 'email')->pluck('value', 'key');
            $email = $rows->toArray();
        } catch (\Throwable $e) {
            // DB not ready yet (fresh install / test) — skip
            return;
        }

        if (empty($email)) {
            return;
        }

        $changed = false;

        if (! empty($email['mail_mailer'])) {
            Config::set('mail.default', $email['mail_mailer']);
            $changed = true;
        }

        if (! empty($email['mail_host'])) {
            Config::set('mail.mailers.smtp.host', $email['mail_host']);
            $changed = true;
        }

        if (! empty($email['mail_port'])) {
            Config::set('mail.mailers.smtp.port', (int) $email['mail_port']);
            $changed = true;
        }

        if (! empty($email['mail_username'])) {
            Config::set('mail.mailers.smtp.username', $email['mail_username']);
            $changed = true;
        }

        if (! empty($email['mail_password'])) {
            Config::set('mail.mailers.smtp.password', $email['mail_password']);
            $changed = true;
        }

        if (isset($email['mail_encryption'])) {
            $raw = $email['mail_encryption'];

            // Laravel 12 / Symfony 7 uses 'scheme' not 'encryption'.
            // Port 587 STARTTLS → scheme=smtp (null encryption)
            // Port 465 SSL      → scheme=smtps
            $port = (int) ($email['mail_port'] ?? 587);
            if ($raw === 'ssl' || $port === 465) {
                $scheme = 'smtps';
                Config::set('mail.mailers.smtp.encryption', 'ssl');
            } elseif ($raw === 'none' || $raw === '') {
                $scheme = 'smtp';
                Config::set('mail.mailers.smtp.encryption', null);
            } else {
                // 'tls' on port 587 = STARTTLS → scheme is still 'smtp'
                $scheme = 'smtp';
                Config::set('mail.mailers.smtp.encryption', null); // Let Symfony negotiate TLS
            }

            Config::set('mail.mailers.smtp.scheme', $scheme);
            $changed = true;
        }

        if (! empty($email['mail_from_address'])) {
            Config::set('mail.from.address', $email['mail_from_address']);
            $changed = true;
        }

        if (! empty($email['mail_from_name'])) {
            Config::set('mail.from.name', $email['mail_from_name']);
            $changed = true;
        }

        // Purge MailManager's resolved mailer instances so next Mail call
        // rebuilds the transport with new config (critical for runtime re-apply)
        if ($changed) {
            $this->purgeMailManager();
        }
    }

    /**
     * Force MailManager to forget resolved mailers & transports.
     * Without this, Config::set() changes are ignored because the SMTP
     * transport was already instantiated with old credentials.
     */
    protected function purgeMailManager(): void
    {
        try {
            $manager = app('mail.manager');
            // Purge all resolved mailers so they rebuild on next use
            $manager->purge('smtp');
            $manager->purge(config('mail.default'));
        } catch (\Throwable $e) {
            // MailManager may not expose purge() in all versions — safe to ignore
        }
    }
}
