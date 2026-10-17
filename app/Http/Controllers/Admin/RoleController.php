<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::withCount(['permissions', 'users'])->get();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9\s]+$/',
                'unique:roles,name',
            ],
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.max' => 'Nama role maksimal 50 karakter.',
            'name.regex' => 'Nama role hanya boleh berisi huruf, angka, dan spasi.',
            'name.unique' => 'Nama role sudah digunakan.',
            'permissions.required' => 'Minimal 1 permission harus dipilih.',
            'permissions.min' => 'Minimal 1 permission harus dipilih.',
            'permissions.*.exists' => 'Permission yang dipilih tidak valid.',
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dibuat.');
    }

    /**
     * Show a role (redirects to edit).
     */
    public function show(Role $role)
    {
        return redirect()->route('admin.roles.edit', $role);
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role Super Admin tidak dapat diubah.');
        }

        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        if ($role->name === 'Super Admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role Super Admin tidak dapat diubah.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9\s]+$/',
                'unique:roles,name,'.$role->id,
            ],
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.max' => 'Nama role maksimal 50 karakter.',
            'name.regex' => 'Nama role hanya boleh berisi huruf, angka, dan spasi.',
            'name.unique' => 'Nama role sudah digunakan.',
            'permissions.required' => 'Minimal 1 permission harus dipilih.',
            'permissions.min' => 'Minimal 1 permission harus dipilih.',
            'permissions.*.exists' => 'Permission yang dipilih tidak valid.',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role Super Admin tidak dapat dihapus.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role tidak dapat dihapus karena masih memiliki pengguna.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}
