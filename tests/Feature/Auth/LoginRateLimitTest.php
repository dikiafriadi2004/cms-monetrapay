<?php

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

beforeEach(function () {
    RateLimiter::clear('test@example.com|127.0.0.1');
});

describe('Login rate limiting', function () {
    it('locks account after 5 failed attempts', function () {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        // 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
        }

        // 6th attempt should be locked
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'correct-password', // even correct password is blocked
        ]);

        $response->assertSessionHasErrors('email');
        $errors = session('errors')->get('email');
        expect($errors[0])->toContain('Terlalu banyak');
    });

    it('records audit log on failed login', function () {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $log = AuditLog::where('action', 'login.failed')->first();
        expect($log)->not->toBeNull();
        expect($log->payload['email'])->toBe('test@example.com');
        expect($log->payload['remaining_attempts'])->toBe(4);
    });

    it('records audit log on successful login', function () {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'correct-password',
        ]);

        $log = AuditLog::where('action', 'login.success')->first();
        expect($log)->not->toBeNull();
        expect($log->user_id)->toBe($user->id);
    });

    it('clears rate limit counter on successful login', function () {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        // 3 failed attempts
        for ($i = 0; $i < 3; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
        }

        expect(RateLimiter::attempts('test@example.com|127.0.0.1'))->toBe(3);

        // Successful login
        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'correct-password',
        ]);

        expect(RateLimiter::attempts('test@example.com|127.0.0.1'))->toBe(0);
    });

    it('treats email case-insensitively for rate limit', function () {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        // 3 attempts with lowercase
        for ($i = 0; $i < 3; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong',
            ]);
        }

        // 2 attempts with uppercase — should share counter
        for ($i = 0; $i < 2; $i++) {
            $this->post('/login', [
                'email' => 'TEST@EXAMPLE.COM',
                'password' => 'wrong',
            ]);
        }

        // 6th attempt should be locked (5 max)
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong',
        ]);

        $errors = session('errors')->get('email');
        expect($errors[0])->toContain('Terlalu banyak');
    });
});
