<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
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

        // Define modules and operations
        $modules = ['blog', 'page', 'category', 'menu', 'file', 'ad', 'setting', 'analytics'];
        $operations = ['view', 'create', 'edit', 'delete'];

        // Create permissions for each module (8 modules × 4 operations = 32 permissions)
        $allPermissions = [];
        foreach ($modules as $module) {
            foreach ($operations as $operation) {
                $permissionName = "{$module}.{$operation}";
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
                $allPermissions[] = $permissionName;
            }
        }

        // Create Super Admin role with ALL permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions($allPermissions);

        // Create Admin role: all permissions EXCEPT setting.* and role management
        $adminPermissions = collect($allPermissions)->filter(function ($permission) {
            return ! str_starts_with($permission, 'setting.');
        })->values()->toArray();

        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($adminPermissions);

        // Create Editor role: view + edit on blog, page, category, file modules
        $editorModules = ['blog', 'page', 'category', 'file'];
        $editorOperations = ['view', 'edit'];
        $editorPermissions = [];
        foreach ($editorModules as $module) {
            foreach ($editorOperations as $operation) {
                $editorPermissions[] = "{$module}.{$operation}";
            }
        }

        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editorRole->syncPermissions($editorPermissions);

        // Create Author role: view + create on blog module only
        $authorPermissions = ['blog.view', 'blog.create'];

        $authorRole = Role::firstOrCreate(['name' => 'Author', 'guard_name' => 'web']);
        $authorRole->syncPermissions($authorPermissions);

        $adminEmail = config('admin.default_email', 'admin@monetrapay.com');
        $adminPassword = config('admin.default_password');

        if (app()->environment('production') && empty($adminPassword)) {
            return;
        }

        // Production admin password must be at least 12 characters with mixed case, numbers, and special characters.
        if (app()->environment('production') && ! empty($adminPassword)) {
            $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{12,}$/';
            if (! preg_match($passwordPattern, $adminPassword)) {
                Log::warning('Weak admin password detected in production seeder. Change immediately after first login.');
            }
        }

        // Create default Super Admin user for local/testing, or production only when explicit credentials are set.
        $superAdmin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => config('admin.default_name', 'Super Admin'),
                'password' => bcrypt($adminPassword ?: 'password'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);
    }
}
