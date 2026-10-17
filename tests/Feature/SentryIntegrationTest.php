<?php

use App\Http\Middleware\SentryContext;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Sentry integration', function () {
    it('SentryContext middleware is a no-op when DSN is empty', function () {
        config(['sentry.dsn' => null]);

        // Hit a normal route — should not throw
        $response = $this->get('/');
        $response->assertStatus(200);
    });

    it('SentryContext middleware does not break authenticated request without DSN', function () {
        config(['sentry.dsn' => null]);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    });

    it('SentryContext middleware extractPrefix returns expected segment', function () {
        $middleware = new SentryContext;
        $reflection = new ReflectionClass($middleware);
        $method = $reflection->getMethod('extractPrefix');
        $method->setAccessible(true);

        expect($method->invoke($middleware, 'admin/blogs/1'))->toBe('admin');
        expect($method->invoke($middleware, 'blog/my-post'))->toBe('blog');
        expect($method->invoke($middleware, ''))->toBe('root');
    });

    it('sentry.dsn is empty by default in test environment', function () {
        // We don't want test runs sending events to Sentry
        expect(config('sentry.dsn'))->toBeFalsy();
    });

    it('sentry config has expected ignore_transactions list', function () {
        $ignored = config('sentry.ignore_transactions', []);
        expect($ignored)->toContain('/up');
    });
});
