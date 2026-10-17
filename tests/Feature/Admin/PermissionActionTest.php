<?php

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['blog', 'page', 'category', 'menu', 'file', 'ad', 'setting', 'analytics'] as $module) {
        foreach (['view', 'create', 'edit', 'delete'] as $operation) {
            Permission::firstOrCreate(['name' => "{$module}.{$operation}", 'guard_name' => 'web']);
        }
    }
});

function userWithPermissions(array $permissions): User
{
    $user = User::factory()->create();
    $role = Role::create([
        'name' => 'role-'.str()->uuid(),
        'guard_name' => 'web',
    ]);
    $role->syncPermissions($permissions);
    $user->assignRole($role);

    return $user;
}

function validBlogPayload(Category $category): array
{
    return [
        'title' => 'Judul Artikel Baru',
        'content' => '<p>Konten artikel yang valid.</p>',
        'categories' => [$category->id],
        'status' => 'draft',
    ];
}

it('allows blog view permission to view blog list only', function () {
    $user = userWithPermissions(['blog.view']);

    $this->actingAs($user)
        ->get(route('admin.blogs.index'))
        ->assertOk();
});

it('rejects blog create actions when user only has blog view permission', function () {
    $user = userWithPermissions(['blog.view']);
    $category = Category::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.blogs.create'))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('admin.blogs.store'), validBlogPayload($category))
        ->assertForbidden();
});

it('rejects blog edit actions when user only has blog view permission', function () {
    $user = userWithPermissions(['blog.view']);
    $category = Category::factory()->create();
    $blog = Blog::factory()->create(['user_id' => $user->id]);
    $blog->categories()->sync([$category->id]);

    $this->actingAs($user)
        ->get(route('admin.blogs.edit', $blog))
        ->assertForbidden();

    $this->actingAs($user)
        ->put(route('admin.blogs.update', $blog), validBlogPayload($category))
        ->assertForbidden();
});

it('rejects blog delete actions when user only has blog view permission', function () {
    $user = userWithPermissions(['blog.view']);
    $blog = Blog::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->delete(route('admin.blogs.destroy', $blog))
        ->assertForbidden();
});

it('allows matching blog action permissions', function () {
    $user = userWithPermissions(['blog.view', 'blog.create', 'blog.edit', 'blog.delete']);
    $category = Category::factory()->create();
    $blog = Blog::factory()->create(['user_id' => $user->id]);
    $blog->categories()->sync([$category->id]);

    $this->actingAs($user)
        ->get(route('admin.blogs.create'))
        ->assertOk();

    $this->actingAs($user)
        ->put(route('admin.blogs.update', $blog), validBlogPayload($category))
        ->assertRedirect(route('admin.blogs.index'));

    $this->actingAs($user)
        ->delete(route('admin.blogs.destroy', $blog))
        ->assertRedirect(route('admin.blogs.index'));
});

it('assigns action-specific permission middleware to admin mutation routes', function (string $routeName, string $middleware) {
    $route = Route::getRoutes()->getByName($routeName);

    expect($route)->not->toBeNull();
    expect($route->gatherMiddleware())->toContain($middleware);
})->with([
    ['admin.settings.update', 'check.permission:setting.edit'],
    ['admin.settings.email-test', 'check.permission:setting.edit'],
    ['admin.contacts.toggle-read', 'check.permission:setting.edit'],
    ['admin.contacts.reply', 'check.permission:setting.edit'],
    ['admin.contacts.destroy', 'check.permission:setting.delete'],
    ['admin.comments.approve', 'check.permission:blog.edit'],
    ['admin.comments.spam', 'check.permission:blog.edit'],
    ['admin.comments.bulk', 'check.permission:blog.edit'],
    ['admin.comments.destroy', 'check.permission:blog.delete'],
    ['admin.newsletter.destroy', 'check.permission:setting.delete'],
]);
