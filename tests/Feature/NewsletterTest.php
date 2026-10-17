<?php

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

beforeEach(function () {
    RateLimiter::clear('newsletter:127.0.0.1');
});

describe('Newsletter subscription', function () {
    it('creates a pending subscriber on subscribe', function () {
        $response = $this->post('/newsletter/subscribe', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        $response->assertSessionHas('newsletter_success');
        expect(NewsletterSubscriber::count())->toBe(1);

        $sub = NewsletterSubscriber::first();
        expect($sub->email)->toBe('test@example.com');
        expect($sub->isConfirmed())->toBeFalse();
        expect($sub->confirmation_token)->not->toBeNull();
    });

    it('rejects invalid email', function () {
        $response = $this->post('/newsletter/subscribe', ['email' => 'not-email']);

        $response->assertSessionHasErrors('email');
        expect(NewsletterSubscriber::count())->toBe(0);
    });

    it('rejects honeypot bot', function () {
        $response = $this->post('/newsletter/subscribe', [
            'email' => 'bot@example.com',
            'website' => 'spam.example.com',
        ]);

        $response->assertSessionHas('newsletter_error');
        expect(NewsletterSubscriber::count())->toBe(0);
    });

    it('confirms subscription with valid token', function () {
        $sub = NewsletterSubscriber::create([
            'email' => 'test@example.com',
            'confirmation_token' => 'test-token-123',
        ]);

        $response = $this->get('/newsletter/confirm/test-token-123');

        $response->assertStatus(200);
        $response->assertSee('Berhasil Berlangganan');

        $sub->refresh();
        expect($sub->isConfirmed())->toBeTrue();
        expect($sub->confirmation_token)->toBeNull();
    });

    it('returns 404 for invalid confirm token', function () {
        $response = $this->get('/newsletter/confirm/invalid-token');
        $response->assertStatus(404);
    });

    it('unsubscribes via valid token', function () {
        $sub = NewsletterSubscriber::create([
            'email' => 'active@example.com',
            'confirmation_token' => 'unsub-token-456',
            'confirmed_at' => now(),
        ]);

        $response = $this->get('/newsletter/unsubscribe/unsub-token-456');

        $response->assertStatus(200);
        $response->assertSee('Berhenti Langganan');
        $sub->refresh();
        expect($sub->isUnsubscribed())->toBeTrue();
    });

    it('reports already-active subscriber', function () {
        NewsletterSubscriber::create([
            'email' => 'existing@example.com',
            'confirmed_at' => now(),
        ]);

        $response = $this->post('/newsletter/subscribe', ['email' => 'existing@example.com']);

        $response->assertSessionHas('newsletter_success');
        $sess = session('newsletter_success');
        expect($sess)->toContain('sudah terdaftar');
        expect(NewsletterSubscriber::count())->toBe(1); // no duplicate
    });

    it('rate-limits after 3 attempts', function () {
        for ($i = 0; $i < 3; $i++) {
            $this->post('/newsletter/subscribe', ['email' => "user{$i}@example.com"]);
        }
        expect(NewsletterSubscriber::count())->toBe(3);

        $response = $this->post('/newsletter/subscribe', ['email' => 'fourth@example.com']);
        $response->assertSessionHas('newsletter_error');
        expect(NewsletterSubscriber::count())->toBe(3);
    });
});
