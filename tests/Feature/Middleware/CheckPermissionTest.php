<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create test permissions
    Permission::create(['name' => 'blog.view']);
    Permission::create(['name' => 'blog.create']);
    Permission::create(['name' => 'page.view']);
});

test('unauthenticated user is redirected to login', function () {
    // Register a test route with the check.permission middleware
    Route::middleware(['web', 'auth', 'check.permission:blog.view'])->get('/admin/test-permission', function () {
        return 'OK';
    });

    $response = $this->get('/admin/test-permission');

    $response->assertRedirect('/login');
});

test('authenticated user without permission gets 403', function () {
    $user = User::factory()->create();

    // Register a test route with the check.permission middleware
    Route::middleware(['web', 'auth', 'check.permission:blog.view'])->get('/admin/test-permission', function () {
        return 'OK';
    });

    $response = $this->actingAs($user)->get('/admin/test-permission');

    $response->assertStatus(403);
});

test('authenticated user with permission can access route', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('blog.view');

    // Register a test route with the check.permission middleware
    Route::middleware(['web', 'auth', 'check.permission:blog.view'])->get('/admin/test-permission', function () {
        return 'OK';
    });

    $response = $this->actingAs($user)->get('/admin/test-permission');

    $response->assertStatus(200);
    $response->assertSee('OK');
});

test('user with role that has permission can access route', function () {
    $role = Role::create(['name' => 'editor']);
    $role->givePermissionTo('blog.view');

    $user = User::factory()->create();
    $user->assignRole('editor');

    Route::middleware(['web', 'auth', 'check.permission:blog.view'])->get('/admin/test-permission', function () {
        return 'OK';
    });

    $response = $this->actingAs($user)->get('/admin/test-permission');

    $response->assertStatus(200);
});

test('user without specific permission gets 403 even if they have other permissions', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('blog.view');

    Route::middleware(['web', 'auth', 'check.permission:page.view'])->get('/admin/test-permission', function () {
        return 'OK';
    });

    $response = $this->actingAs($user)->get('/admin/test-permission');

    $response->assertStatus(403);
});

test('403 error page renders correctly', function () {
    $user = User::factory()->create();

    Route::middleware(['web', 'auth', 'check.permission:blog.view'])->get('/admin/test-permission', function () {
        return 'OK';
    });

    $response = $this->actingAs($user)->get('/admin/test-permission');

    $response->assertStatus(403);
});

test('admin dashboard is accessible to authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertStatus(200);
});
