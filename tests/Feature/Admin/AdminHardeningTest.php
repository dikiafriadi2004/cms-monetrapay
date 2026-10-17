<?php

use App\Models\Ad;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

function hardeningUserWithPermissions(array $permissions): User
{
    $user = User::factory()->create();
    $role = Role::create([
        'name' => 'hardening-role-'.str()->uuid(),
        'guard_name' => 'web',
    ]);
    $role->syncPermissions($permissions);
    $user->assignRole($role);

    return $user;
}

function validPagePayload(array $overrides = []): array
{
    return array_merge([
        'title' => 'Halaman Hardening',
        'slug' => 'halaman-hardening',
        'content' => '<p>Konten halaman hardening.</p>',
        'template' => 'default',
        'status' => 'draft',
    ], $overrides);
}

function validAdPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Slot Iklan Hardening',
        'type' => 'manual',
        'code' => '<div>Iklan aman</div>',
        'position' => 'sidebar',
        'is_active' => '1',
    ], $overrides);
}

it('rejects svg uploads in the file manager', function () {
    Storage::fake('public');
    $user = hardeningUserWithPermissions(['file.create']);
    $svg = UploadedFile::fake()->create('danger.svg', 1, 'image/svg+xml');

    $this->actingAs($user)
        ->from(route('admin.files.index'))
        ->post(route('admin.files.upload'), ['file' => $svg])
        ->assertSessionHasErrors('file');

    Storage::disk('public')->assertMissing('media/'.$svg->hashName());
});

it('does not create the default password admin in production without explicit credentials', function () {
    app()->detectEnvironment(fn () => 'production');

    app(RolePermissionSeeder::class)->run();

    expect(User::where('email', 'admin@monetrapay.com')->exists())->toBeFalse();
});

it('covers page create update and delete flows', function () {
    $user = hardeningUserWithPermissions(['page.view', 'page.create', 'page.edit', 'page.delete']);

    $this->actingAs($user)
        ->post(route('admin.pages.store'), validPagePayload())
        ->assertRedirect(route('admin.pages.index'));

    $page = Page::where('slug', 'halaman-hardening')->firstOrFail();
    expect($page->title)->toBe('Halaman Hardening');

    $this->actingAs($user)
        ->put(route('admin.pages.update', $page), validPagePayload([
            'title' => 'Halaman Hardening Update',
            'slug' => 'halaman-hardening-update',
            'status' => 'published',
        ]))
        ->assertRedirect(route('admin.pages.index'));

    $page->refresh();
    expect($page->title)->toBe('Halaman Hardening Update');
    expect($page->status)->toBe('published');

    $this->actingAs($user)
        ->delete(route('admin.pages.destroy', $page))
        ->assertRedirect(route('admin.pages.index'));

    expect(Page::onlyTrashed()->whereKey($page->id)->exists())->toBeTrue();
});

it('covers ad create update toggle and delete flows', function () {
    $user = hardeningUserWithPermissions(['ad.view', 'ad.create', 'ad.edit', 'ad.delete']);

    $this->actingAs($user)
        ->post(route('admin.ads.store'), validAdPayload())
        ->assertRedirect(route('admin.ads.index'));

    $ad = Ad::where('name', 'Slot Iklan Hardening')->firstOrFail();
    expect($ad->position)->toBe('sidebar');

    $this->actingAs($user)
        ->put(route('admin.ads.update', $ad), validAdPayload([
            'name' => 'Slot Iklan Hardening Update',
            'position' => 'footer',
            'is_active' => '0',
        ]))
        ->assertRedirect(route('admin.ads.index'));

    $ad->refresh();
    expect($ad->name)->toBe('Slot Iklan Hardening Update');
    expect($ad->position)->toBe('footer');

    $this->actingAs($user)
        ->post(route('admin.ads.toggle', $ad))
        ->assertRedirect(route('admin.ads.index'));

    $ad->refresh();
    expect($ad->is_active)->toBeTrue();

    $this->actingAs($user)
        ->delete(route('admin.ads.destroy', $ad))
        ->assertRedirect(route('admin.ads.index'));

    expect(Ad::whereKey($ad->id)->exists())->toBeFalse();
});
