<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Standard modules with 4 operations (view, create, edit, delete)
        $standardModules = ['blog', 'page', 'category', 'menu', 'file', 'ad', 'setting', 'user', 'role', 'newsletter'];
        $operations = ['view', 'create', 'edit', 'delete'];

        // Analytics — view only (no create/edit/delete)
        $analyticsPermissions = ['analytics.view'];

        // Contact — no create (submissions come from frontend form)
        $contactPermissions = ['contact.view', 'contact.edit', 'contact.delete'];

        // Build all permissions
        $allPermissions = [];

        // Standard modules × 4 operations = 40 permissions
        foreach ($standardModules as $module) {
            foreach ($operations as $operation) {
                $permissionName = "{$module}.{$operation}";
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
                $allPermissions[] = $permissionName;
            }
        }

        // Analytics: 1 permission
        foreach ($analyticsPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            $allPermissions[] = $permissionName;
        }

        // Contact: 3 permissions
        foreach ($contactPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            $allPermissions[] = $permissionName;
        }

        // Total: 40 + 1 + 3 = 44 permissions

        // Super Admin: all 44 permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions($allPermissions);

        // Admin: all EXCEPT setting.* and role.*
        $adminPermissions = collect($allPermissions)->filter(function ($permission) {
            return ! str_starts_with($permission, 'setting.')
                && ! str_starts_with($permission, 'role.');
        })->values()->toArray();

        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($adminPermissions);

        // Editor: view + edit on blog, page, category, file + newsletter.view
        $editorPermissions = [
            'blog.view', 'blog.edit',
            'page.view', 'page.edit',
            'category.view', 'category.edit',
            'file.view', 'file.edit',
            'newsletter.view',
        ];

        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editorRole->syncPermissions($editorPermissions);

        // Author: view + create on blog only
        $authorPermissions = ['blog.view', 'blog.create'];

        $authorRole = Role::firstOrCreate(['name' => 'Author', 'guard_name' => 'web']);
        $authorRole->syncPermissions($authorPermissions);

        // Kredensial super admin pertama — hardcoded di seeder, tidak perlu di .env.
        $adminEmail    = 'admin@monetrapay.co.id';
        $adminPassword = '20041992';
        $adminName     = 'Diki Afriadi';

        $superAdmin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name'     => $adminName,
                'password' => bcrypt($adminPassword),
            ]
        );
        $superAdmin->syncRoles([$superAdminRole]);

        $this->command->info('✅ Roles & permissions seeded (44 total). Admin: ' . $adminEmail);
    }
}
