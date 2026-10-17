<?php

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['menu.view', 'menu.edit'] as $permission) {
        Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
    }
});

function menuBuilderUser(): User
{
    $user = User::factory()->create();
    $role = Role::create(['name' => 'menu-builder-role-'.str()->uuid(), 'guard_name' => 'web']);
    $role->syncPermissions(['menu.view', 'menu.edit']);
    $user->assignRole($role);

    return $user;
}

it('renders sortable drag and drop wiring for existing menu items', function () {
    $menu = Menu::create(['name' => 'Header Menu', 'location' => 'header', 'is_active' => true]);
    MenuItem::create([
        'menu_id' => $menu->id,
        'type' => 'custom',
        'label' => 'Home',
        'url' => 'https://example.test',
        'target' => '_self',
        'order' => 0,
    ]);

    $this->actingAs(menuBuilderUser())
        ->get(route('admin.menus.edit', $menu))
        ->assertOk()
        ->assertSee('@dragstart.stop', false)
        ->assertSee('dropItem(items, index', false)
        ->assertSee('dropInto(item.children', false)
        ->assertDontSee('Sortable.create', false)
        ->assertDontSee('cdn.jsdelivr.net/npm/sortablejs', false)
        ->assertDontSee('lastElementChild', false)
        ->assertSee('data-parent-id', false)
        ->assertSee('Home');
});

it('saves reordered existing menu items without losing item data', function () {
    $menu = Menu::create(['name' => 'Header Menu', 'location' => 'header', 'is_active' => true]);
    MenuItem::create([
        'menu_id' => $menu->id,
        'type' => 'custom',
        'label' => 'First',
        'url' => 'https://first.test',
        'target' => '_self',
        'order' => 0,
    ]);
    MenuItem::create([
        'menu_id' => $menu->id,
        'type' => 'custom',
        'label' => 'Second',
        'url' => 'https://second.test',
        'target' => '_self',
        'order' => 1,
    ]);

    $this->actingAs(menuBuilderUser())
        ->putJson(route('admin.menus.save-items', $menu), [
            'items' => [
                ['label' => 'Second', 'type' => 'custom', 'url' => 'https://second.test', 'target' => '_self', 'children' => []],
                ['label' => 'First', 'type' => 'custom', 'url' => 'https://first.test', 'target' => '_self', 'children' => []],
            ],
        ])
        ->assertOk()
        ->assertJsonPath('success', true);

    expect(MenuItem::where('menu_id', $menu->id)->orderBy('order')->pluck('label')->all())
        ->toBe(['Second', 'First']);
});
