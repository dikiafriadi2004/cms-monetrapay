<?php

use App\Mail\ContactConfirmation;
use App\Mail\ContactNotification;
use App\Models\ContactSubmission;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

beforeEach(function () {
    RateLimiter::clear('contact:127.0.0.1');
    Mail::fake();
});

describe('Contact form submission', function () {
    it('creates a submission with valid data', function () {
        $payload = [
            '_t' => time() - 5,
            'name' => 'Andi',
            'phone' => '081234567890',
            'email' => 'andi@example.com',
            'city' => 'Jakarta',
            'message' => 'Saya tertarik menjadi agen.',
        ];

        $response = $this->post('/kontak', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('contact_success');
        expect(ContactSubmission::count())->toBe(1);

        $sub = ContactSubmission::first();
        expect($sub->name)->toBe('Andi');
        expect($sub->phone)->toBe('081234567890');
        expect($sub->is_read)->toBeFalse();
        expect($sub->ip_address)->not->toBeNull();
    });

    it('rejects submission when honeypot is filled', function () {
        $response = $this->post('/kontak', [
            '_t' => time() - 5,
            'name' => 'Bot',
            'phone' => '081234567890',
            'website' => 'spam.com',
        ]);

        $response->assertSessionHas('contact_error');
        expect(ContactSubmission::count())->toBe(0);
    });

    it('rejects submission when filled too quickly (bot)', function () {
        $response = $this->post('/kontak', [
            '_t' => time(), // 0 seconds elapsed
            'name' => 'Bot',
            'phone' => '081234567890',
        ]);

        $response->assertSessionHas('contact_error');
        expect(ContactSubmission::count())->toBe(0);
    });

    it('rejects submission without time token', function () {
        $response = $this->post('/kontak', [
            'name' => 'NoToken',
            'phone' => '081234567890',
        ]);

        $response->assertSessionHas('contact_error');
        expect(ContactSubmission::count())->toBe(0);
    });

    it('validates required fields', function () {
        $response = $this->post('/kontak', [
            '_t' => time() - 5,
            'name' => 'A', // too short
            'phone' => '123', // too short
        ]);

        $response->assertSessionHasErrors(['name', 'phone']);
        expect(ContactSubmission::count())->toBe(0);
    });

    it('validates phone format', function () {
        $response = $this->post('/kontak', [
            '_t' => time() - 5,
            'name' => 'Andi',
            'phone' => 'abc-not-phone',
        ]);

        $response->assertSessionHasErrors(['phone']);
        expect(ContactSubmission::count())->toBe(0);
    });

    it('rate limits after 3 submissions per IP', function () {
        $payload = [
            '_t' => time() - 5,
            'name' => 'Andi',
            'phone' => '081234567890',
        ];

        // 3 valid submissions
        for ($i = 0; $i < 3; $i++) {
            $this->post('/kontak', $payload);
        }

        expect(ContactSubmission::count())->toBe(3);

        // 4th should be rate limited
        $response = $this->post('/kontak', $payload);
        $response->assertSessionHas('contact_error');
        expect(ContactSubmission::count())->toBe(3);
    });

    it('sends auto-reply to submitter when email provided', function () {
        $this->post('/kontak', [
            '_t' => time() - 5,
            'name' => 'Andi',
            'phone' => '081234567890',
            'email' => 'andi@example.com',
        ]);

        Mail::assertQueued(ContactConfirmation::class, function (ContactConfirmation $mail) {
            return $mail->hasTo('andi@example.com');
        });
    });

    it('does not send auto-reply when email is missing', function () {
        $this->post('/kontak', [
            '_t' => time() - 5,
            'name' => 'Andi',
            'phone' => '081234567890',
        ]);

        Mail::assertNotQueued(ContactConfirmation::class);
    });

    it('sends notification to admin', function () {
        // set admin email
        Setting::create(['group' => 'general', 'key' => 'contact_email', 'value' => 'admin@site.com']);

        $this->post('/kontak', [
            '_t' => time() - 5,
            'name' => 'Andi',
            'phone' => '081234567890',
            'email' => 'andi@example.com',
        ]);

        Mail::assertQueued(ContactNotification::class, function (ContactNotification $mail) {
            return $mail->hasTo('admin@site.com');
        });
    });
});
