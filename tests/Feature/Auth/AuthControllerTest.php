<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Login Page', function () {
    it('shows the login form', function () {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('MonetraCMS');
        $response->assertSee('Email');
        $response->assertSee('Password');
    });

    it('redirects authenticated users to dashboard', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect(route('admin.dashboard'));
    });
});

describe('Login', function () {
    it('authenticates user with valid credentials', function () {
        $user = User::factory()->create([
            'email' => 'admin@monetrapay.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@monetrapay.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    });

    it('authenticates user with remember me', function () {
        $user = User::factory()->create([
            'email' => 'admin@monetrapay.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@monetrapay.com',
            'password' => 'password123',
            'remember' => true,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    });

    it('rejects invalid credentials', function () {
        $user = User::factory()->create([
            'email' => 'admin@monetrapay.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@monetrapay.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });

    it('validates email is required', function () {
        $response = $this->post(route('login'), [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });

    it('validates password is required', function () {
        $response = $this->post(route('login'), [
            'email' => 'admin@monetrapay.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    });

    it('validates email format', function () {
        $response = $this->post(route('login'), [
            'email' => 'not-an-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });
});

describe('Logout', function () {
    it('logs out authenticated user', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    });

    it('requires authentication to logout', function () {
        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
    });
});

describe('Admin Dashboard Access', function () {
    it('redirects unauthenticated users to login', function () {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    });

    it('allows authenticated users to access dashboard', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    });
});
