<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create permissions
    $modules = ['blog', 'page', 'category', 'menu', 'file', 'ad', 'setting', 'analytics'];
    $operations = ['view', 'create', 'edit', 'delete'];

    foreach ($modules as $module) {
        foreach ($operations as $operation) {
            Permission::firstOrCreate(['name' => "{$module}.{$operation}", 'guard_name' => 'web']);
        }
    }

    // Create Super Admin role with all permissions
    $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $superAdminRole->syncPermissions(Permission::all());

    // Create admin user with setting.view permission
    $this->adminUser = User::factory()->create();
    $this->adminUser->assignRole($superAdminRole);
});

describe('Role Index', function () {
    it('displays the roles list page', function () {
        $response = $this->actingAs($this->adminUser)->get(route('admin.roles.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Role');
        $response->assertSee('Super Admin');
    });

    it('shows permissions count and users count', function () {
        $response = $this->actingAs($this->adminUser)->get(route('admin.roles.index'));

        $response->assertStatus(200);
        $response->assertSee('permission');
        $response->assertSee('user');
    });

    it('requires authentication', function () {
        $response = $this->get(route('admin.roles.index'));

        $response->assertRedirect(route('login'));
    });

    it('requires setting.view permission', function () {
        $user = User::factory()->create();
        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editorRole->syncPermissions(['blog.view', 'blog.edit']);
        $user->assignRole($editorRole);

        $response = $this->actingAs($user)->get(route('admin.roles.index'));

        $response->assertStatus(403);
    });
});

describe('Role Create', function () {
    it('displays the create role form', function () {
        $response = $this->actingAs($this->adminUser)->get(route('admin.roles.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Role Baru');
        $response->assertSee('blog');
        $response->assertSee('setting');
    });

    it('shows permissions grouped by module', function () {
        $response = $this->actingAs($this->adminUser)->get(route('admin.roles.create'));

        $response->assertStatus(200);
        $response->assertSee('Blog');
        $response->assertSee('Page');
        $response->assertSee('Setting');
    });
});

describe('Role Store', function () {
    it('creates a new role with permissions', function () {
        $response = $this->actingAs($this->adminUser)->post(route('admin.roles.store'), [
            'name' => 'Content Manager',
            'permissions' => ['blog.view', 'blog.create', 'blog.edit'],
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success');

        $role = Role::where('name', 'Content Manager')->first();
        expect($role)->not->toBeNull();
        expect($role->permissions->pluck('name')->toArray())->toContain('blog.view', 'blog.create', 'blog.edit');
    });

    it('validates name is required', function () {
        $response = $this->actingAs($this->adminUser)->post(route('admin.roles.store'), [
            'name' => '',
            'permissions' => ['blog.view'],
        ]);

        $response->assertSessionHasErrors('name');
    });

    it('validates name max 50 characters', function () {
        $response = $this->actingAs($this->adminUser)->post(route('admin.roles.store'), [
            'name' => str_repeat('a', 51),
            'permissions' => ['blog.view'],
        ]);

        $response->assertSessionHasErrors('name');
    });

    it('validates name is alphanumeric with spaces only', function () {
        $response = $this->actingAs($this->adminUser)->post(route('admin.roles.store'), [
            'name' => 'Invalid@Role!',
            'permissions' => ['blog.view'],
        ]);

        $response->assertSessionHasErrors('name');
    });

    it('validates name is unique', function () {
        Role::create(['name' => 'Editor', 'guard_name' => 'web']);

        $response = $this->actingAs($this->adminUser)->post(route('admin.roles.store'), [
            'name' => 'Editor',
            'permissions' => ['blog.view'],
        ]);

        $response->assertSessionHasErrors('name');
    });

    it('validates at least 1 permission is required', function () {
        $response = $this->actingAs($this->adminUser)->post(route('admin.roles.store'), [
            'name' => 'Empty Role',
            'permissions' => [],
        ]);

        $response->assertSessionHasErrors('permissions');
    });

    it('validates permissions exist', function () {
        $response = $this->actingAs($this->adminUser)->post(route('admin.roles.store'), [
            'name' => 'Bad Role',
            'permissions' => ['nonexistent.permission'],
        ]);

        $response->assertSessionHasErrors('permissions.0');
    });
});

describe('Role Edit', function () {
    it('displays the edit form for a role', function () {
        $role = Role::create(['name' => 'Editor', 'guard_name' => 'web']);
        $role->syncPermissions(['blog.view', 'blog.edit']);

        $response = $this->actingAs($this->adminUser)->get(route('admin.roles.edit', $role));

        $response->assertStatus(200);
        $response->assertSee('Edit Role');
        $response->assertSee('Editor');
    });

    it('prevents editing Super Admin role', function () {
        $superAdmin = Role::where('name', 'Super Admin')->first();

        $response = $this->actingAs($this->adminUser)->get(route('admin.roles.edit', $superAdmin));

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('error');
    });
});

describe('Role Update', function () {
    it('updates a role name and permissions', function () {
        $role = Role::create(['name' => 'Editor', 'guard_name' => 'web']);
        $role->syncPermissions(['blog.view']);

        $response = $this->actingAs($this->adminUser)->put(route('admin.roles.update', $role), [
            'name' => 'Senior Editor',
            'permissions' => ['blog.view', 'blog.edit', 'page.view'],
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success');

        $role->refresh();
        expect($role->name)->toBe('Senior Editor');
        expect($role->permissions->pluck('name')->toArray())->toContain('blog.view', 'blog.edit', 'page.view');
    });

    it('prevents updating Super Admin role', function () {
        $superAdmin = Role::where('name', 'Super Admin')->first();

        $response = $this->actingAs($this->adminUser)->put(route('admin.roles.update', $superAdmin), [
            'name' => 'Hacked Admin',
            'permissions' => ['blog.view'],
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('error');

        $superAdmin->refresh();
        expect($superAdmin->name)->toBe('Super Admin');
    });

    it('validates unique name excluding current role', function () {
        $role1 = Role::create(['name' => 'Editor', 'guard_name' => 'web']);
        $role2 = Role::create(['name' => 'Author', 'guard_name' => 'web']);

        // Updating role2 to have the same name as role1 should fail
        $response = $this->actingAs($this->adminUser)->put(route('admin.roles.update', $role2), [
            'name' => 'Editor',
            'permissions' => ['blog.view'],
        ]);

        $response->assertSessionHasErrors('name');
    });

    it('allows keeping the same name on update', function () {
        $role = Role::create(['name' => 'Editor', 'guard_name' => 'web']);
        $role->syncPermissions(['blog.view']);

        $response = $this->actingAs($this->adminUser)->put(route('admin.roles.update', $role), [
            'name' => 'Editor',
            'permissions' => ['blog.view', 'blog.edit'],
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success');
    });
});

describe('Role Destroy', function () {
    it('deletes a role without users', function () {
        $role = Role::create(['name' => 'Temporary', 'guard_name' => 'web']);
        $role->syncPermissions(['blog.view']);

        $response = $this->actingAs($this->adminUser)->delete(route('admin.roles.destroy', $role));

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success');
        expect(Role::where('name', 'Temporary')->exists())->toBeFalse();
    });

    it('prevents deleting Super Admin role', function () {
        $superAdmin = Role::where('name', 'Super Admin')->first();

        $response = $this->actingAs($this->adminUser)->delete(route('admin.roles.destroy', $superAdmin));

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('error');
        expect(Role::where('name', 'Super Admin')->exists())->toBeTrue();
    });

    it('prevents deleting a role that has users', function () {
        $role = Role::create(['name' => 'Active Role', 'guard_name' => 'web']);
        $role->syncPermissions(['blog.view']);

        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($this->adminUser)->delete(route('admin.roles.destroy', $role));

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('error');
        expect(Role::where('name', 'Active Role')->exists())->toBeTrue();
    });
});
