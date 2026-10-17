<?php

use App\Models\Ad;
use App\Models\AuditLog;
use App\Models\Blog;
use App\Models\ContactSubmission;
use App\Models\Media;
use App\Models\NewsletterSubscriber;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
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

function nextFeatureAdmin(array $permissions = ['blog.view', 'page.view', 'file.view', 'file.edit', 'setting.view', 'setting.edit', 'analytics.view']): User
{
    $user = User::factory()->create();
    $role = Role::create([
        'name' => 'next-feature-role-'.str()->uuid(),
        'guard_name' => 'web',
    ]);
    $role->syncPermissions($permissions);
    $user->assignRole($role);

    return $user;
}

it('shows expanded dashboard analytics widgets', function () {
    $admin = nextFeatureAdmin();
    Blog::factory()->published()->count(2)->create(['published_at' => now()]);
    Blog::factory()->draft()->create(['created_at' => now()->subDays(45)]);
    Page::factory()->count(2)->create(['status' => 'published']);
    NewsletterSubscriber::create([
        'email' => 'active@example.test',
        'confirmation_token' => NewsletterSubscriber::generateToken(),
        'confirmed_at' => now(),
    ]);
    ContactSubmission::create([
        'name' => 'Lead Baru',
        'phone' => '08123456789',
        'email' => 'lead@example.test',
        'message' => 'Butuh info',
        'is_read' => false,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Subscriber Aktif')
        ->assertSee('Komentar Pending')
        ->assertSee('Draft Lama')
        ->assertSee('Health Check');
});

it('records audit logs for ads and media changes', function () {
    $admin = nextFeatureAdmin(['ad.create', 'file.create']);
    $this->actingAs($admin);

    Ad::create([
        'user_id' => $admin->id,
        'name' => 'Audit Ad',
        'type' => 'manual',
        'code' => '<div>ad</div>',
        'position' => 'sidebar',
        'is_active' => true,
    ]);

    Media::create([
        'folder_id' => null,
        'user_id' => $admin->id,
        'filename' => 'audit.jpg',
        'original_name' => 'audit.jpg',
        'mime_type' => 'image/jpeg',
        'size' => 123,
        'path' => 'media/audit.jpg',
    ]);

    expect(AuditLog::where('action', 'ad.created')->exists())->toBeTrue();
    expect(AuditLog::where('action', 'media.created')->exists())->toBeTrue();
});

it('provides seo tool routes for preview and sitemap regeneration', function () {
    expect(Route::has('admin.seo.index'))->toBeTrue();
    expect(Route::has('admin.seo.preview'))->toBeTrue();
    expect(Route::has('admin.seo.regenerate-sitemap'))->toBeTrue();
});

it('updates media alt text and original name', function () {
    Storage::fake('public');
    $admin = nextFeatureAdmin(['file.view', 'file.edit']);
    $media = Media::create([
        'folder_id' => null,
        'user_id' => $admin->id,
        'filename' => 'old.jpg',
        'original_name' => 'old.jpg',
        'mime_type' => 'image/jpeg',
        'size' => 123,
        'path' => 'media/old.jpg',
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.files.update', $media), [
            'original_name' => 'new-name.jpg',
            'alt_text' => 'Foto produk Monetrapay',
        ])
        ->assertRedirect(route('admin.files.index'));

    $media->refresh();
    expect($media->original_name)->toBe('new-name.jpg');
    expect($media->alt_text)->toBe('Foto produk Monetrapay');
});

it('provides maintenance actions for cache clearing and sitemap regeneration', function () {
    expect(Route::has('admin.maintenance.clear-cache'))->toBeTrue();
    expect(Route::has('admin.maintenance.regenerate-sitemap'))->toBeTrue();

    Artisan::shouldReceive('call')->once()->with('optimize:clear')->andReturn(0);

    $admin = nextFeatureAdmin(['setting.edit']);
    $this->actingAs($admin)
        ->post(route('admin.maintenance.clear-cache'))
        ->assertRedirect()
        ->assertSessionHas('success');
});

it('renders canonical seo preview using public page url', function () {
    $admin = nextFeatureAdmin(['setting.view']);
    Setting::create(['group' => 'seo', 'key' => 'meta_title_default', 'value' => 'MonetraPay Default']);
    $page = Page::factory()->create([
        'title' => 'Tentang Kami',
        'slug' => 'tentang-kami',
        'status' => 'published',
        'meta_title' => 'Tentang Monetrapay',
        'meta_description' => 'Deskripsi tentang Monetrapay.',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.seo.preview', ['type' => 'page', 'id' => $page->id]))
        ->assertOk()
        ->assertJsonPath('canonical_url', url('/tentang-kami'))
        ->assertJsonPath('meta.title', 'Tentang Monetrapay');
});
