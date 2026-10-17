<?php

use App\Models\Blog;
use App\Models\Comment;
use App\Models\ContactSubmission;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

describe('Menu cache invalidation', function () {
    it('clears cache when menu is created', function () {
        $location = 'header';
        Cache::put('menu.location.'.$location, 'cached_data', 60);

        expect(Cache::has('menu.location.'.$location))->toBeTrue();

        Menu::create([
            'name' => 'Header Menu',
            'location' => $location,
            'is_active' => true,
        ]);

        expect(Cache::has('menu.location.'.$location))->toBeFalse();
    });

    it('clears cache when menu is updated', function () {
        $menu = Menu::create([
            'name' => 'Header Menu',
            'location' => 'header',
            'is_active' => true,
        ]);

        Cache::put('menu.location.header', 'cached_data', 60);
        expect(Cache::has('menu.location.header'))->toBeTrue();

        $menu->update(['name' => 'Updated Header Menu']);

        expect(Cache::has('menu.location.header'))->toBeFalse();
    });

    it('clears cache when menu is deleted', function () {
        $menu = Menu::create([
            'name' => 'Footer Menu',
            'location' => 'footer',
            'is_active' => true,
        ]);

        Cache::put('menu.location.footer', 'cached_data', 60);
        expect(Cache::has('menu.location.footer'))->toBeTrue();

        $menu->delete();

        expect(Cache::has('menu.location.footer'))->toBeFalse();
    });

    it('clears both old and new location cache when location changes', function () {
        $menu = Menu::create([
            'name' => 'Menu',
            'location' => 'header',
            'is_active' => true,
        ]);

        Cache::put('menu.location.header', 'old_data', 60);
        Cache::put('menu.location.footer', 'new_data', 60);

        expect(Cache::has('menu.location.header'))->toBeTrue();
        expect(Cache::has('menu.location.footer'))->toBeTrue();

        $menu->update(['location' => 'footer']);

        expect(Cache::has('menu.location.header'))->toBeFalse();
        expect(Cache::has('menu.location.footer'))->toBeFalse();
    });
});

describe('MenuItem cache invalidation', function () {
    it('clears parent menu cache when menu item is created', function () {
        $menu = Menu::create([
            'name' => 'Main Menu',
            'location' => 'header',
            'is_active' => true,
        ]);

        Cache::put('menu.location.header', 'cached_menu', 60);
        expect(Cache::has('menu.location.header'))->toBeTrue();

        MenuItem::create([
            'menu_id' => $menu->id,
            'type' => 'custom',
            'label' => 'Home',
            'url' => '/',
            'order' => 1,
        ]);

        expect(Cache::has('menu.location.header'))->toBeFalse();
    });

    it('clears parent menu cache when menu item is updated', function () {
        $menu = Menu::create([
            'name' => 'Main Menu',
            'location' => 'header',
            'is_active' => true,
        ]);

        $item = MenuItem::create([
            'menu_id' => $menu->id,
            'type' => 'custom',
            'label' => 'Home',
            'url' => '/',
            'order' => 1,
        ]);

        Cache::put('menu.location.header', 'cached_menu', 60);
        expect(Cache::has('menu.location.header'))->toBeTrue();

        $item->update(['label' => 'Homepage']);

        expect(Cache::has('menu.location.header'))->toBeFalse();
    });

    it('clears parent menu cache when menu item is deleted', function () {
        $menu = Menu::create([
            'name' => 'Main Menu',
            'location' => 'header',
            'is_active' => true,
        ]);

        $item = MenuItem::create([
            'menu_id' => $menu->id,
            'type' => 'custom',
            'label' => 'About',
            'url' => '/about',
            'order' => 2,
        ]);

        Cache::put('menu.location.header', 'cached_menu', 60);
        expect(Cache::has('menu.location.header'))->toBeTrue();

        $item->delete();

        expect(Cache::has('menu.location.header'))->toBeFalse();
    });
});

describe('Setting cache invalidation', function () {
    it('clears group cache when setting is created', function () {
        Cache::put('settings.general', ['key1' => 'value1'], 60);
        expect(Cache::has('settings.general'))->toBeTrue();

        Setting::create([
            'key' => 'site_name',
            'value' => 'My Site',
            'group' => 'general',
        ]);

        expect(Cache::has('settings.general'))->toBeFalse();
    });

    it('clears group cache when setting is updated', function () {
        $setting = Setting::create([
            'key' => 'site_name',
            'value' => 'My Site',
            'group' => 'general',
        ]);

        Cache::put('settings.general', ['site_name' => 'My Site'], 60);
        expect(Cache::has('settings.general'))->toBeTrue();

        $setting->update(['value' => 'Updated Site Name']);

        expect(Cache::has('settings.general'))->toBeFalse();
    });

    it('clears group cache when setting is deleted', function () {
        $setting = Setting::create([
            'key' => 'maintenance_mode',
            'value' => 'false',
            'group' => 'general',
        ]);

        Cache::put('settings.general', ['maintenance_mode' => 'false'], 60);
        expect(Cache::has('settings.general'))->toBeTrue();

        $setting->delete();

        expect(Cache::has('settings.general'))->toBeFalse();
    });

    it('only clears cache for the affected group', function () {
        Setting::create([
            'key' => 'key1',
            'value' => 'value1',
            'group' => 'general',
        ]);

        Cache::put('settings.general', ['key1' => 'value1'], 60);
        Cache::put('settings.email', ['smtp_host' => 'localhost'], 60);

        expect(Cache::has('settings.general'))->toBeTrue();
        expect(Cache::has('settings.email'))->toBeTrue();

        Setting::create([
            'key' => 'key2',
            'value' => 'value2',
            'group' => 'general',
        ]);

        expect(Cache::has('settings.general'))->toBeFalse();
        expect(Cache::has('settings.email'))->toBeTrue();
    });
});

describe('Comment cache invalidation', function () {
    it('clears pending count cache when comment is created', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
        ]);

        Cache::put('comments.pending.count', 5, 60);
        expect(Cache::has('comments.pending.count'))->toBeTrue();

        Comment::create([
            'blog_id' => $blog->id,
            'author_name' => 'John Doe',
            'author_email' => 'john@example.com',
            'content' => 'Great article!',
            'status' => Comment::STATUS_PENDING,
        ]);

        expect(Cache::has('comments.pending.count'))->toBeFalse();
    });

    it('clears pending count cache when comment status is updated', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
        ]);

        $comment = Comment::create([
            'blog_id' => $blog->id,
            'author_name' => 'Jane Doe',
            'author_email' => 'jane@example.com',
            'content' => 'Nice post!',
            'status' => Comment::STATUS_PENDING,
        ]);

        Cache::put('comments.pending.count', 1, 60);
        expect(Cache::has('comments.pending.count'))->toBeTrue();

        $comment->update(['status' => Comment::STATUS_APPROVED]);

        expect(Cache::has('comments.pending.count'))->toBeFalse();
    });

    it('clears pending count cache when comment is deleted', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
        ]);

        $comment = Comment::create([
            'blog_id' => $blog->id,
            'author_name' => 'Test User',
            'author_email' => 'test@example.com',
            'content' => 'Test comment',
            'status' => Comment::STATUS_APPROVED,
        ]);

        Cache::put('comments.pending.count', 10, 60);
        expect(Cache::has('comments.pending.count'))->toBeTrue();

        $comment->delete();

        expect(Cache::has('comments.pending.count'))->toBeFalse();
    });
});

describe('ContactSubmission cache invalidation', function () {
    it('clears unread count cache when contact is created', function () {
        Cache::put('contacts.unread.count', 3, 60);
        expect(Cache::has('contacts.unread.count'))->toBeTrue();

        ContactSubmission::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '08123456789',
            'city' => 'Jakarta',
            'message' => 'Hello, I need help',
            'is_read' => false,
        ]);

        expect(Cache::has('contacts.unread.count'))->toBeFalse();
    });

    it('clears unread count cache when contact is marked as read', function () {
        $contact = ContactSubmission::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '08198765432',
            'city' => 'Bandung',
            'message' => 'Question about services',
            'is_read' => false,
        ]);

        Cache::put('contacts.unread.count', 5, 60);
        expect(Cache::has('contacts.unread.count'))->toBeTrue();

        $contact->update(['is_read' => true]);

        expect(Cache::has('contacts.unread.count'))->toBeFalse();
    });

    it('clears unread count cache when contact is deleted', function () {
        $contact = ContactSubmission::create([
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'phone' => '08111222333',
            'city' => 'Surabaya',
            'message' => 'Inquiry about pricing',
            'is_read' => false,
        ]);

        Cache::put('contacts.unread.count', 8, 60);
        expect(Cache::has('contacts.unread.count'))->toBeTrue();

        $contact->delete();

        expect(Cache::has('contacts.unread.count'))->toBeFalse();
    });

    it('clears unread count cache when contact reply is added', function () {
        $user = User::factory()->create();

        $contact = ContactSubmission::create([
            'name' => 'Alice Brown',
            'email' => 'alice@example.com',
            'phone' => '08555666777',
            'city' => 'Medan',
            'message' => 'Product question',
            'is_read' => true,
        ]);

        Cache::put('contacts.unread.count', 2, 60);
        expect(Cache::has('contacts.unread.count'))->toBeTrue();

        $contact->update([
            'admin_reply' => 'Thank you for your inquiry.',
            'replied_at' => now(),
            'replied_by' => $user->id,
        ]);

        expect(Cache::has('contacts.unread.count'))->toBeFalse();
    });
});
