<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Role as PermissionRole;

class UserController extends Controller
{
    /**
     * Role hierarchy (higher index = higher privilege).
     * Actor may only assign roles strictly below their own level
     * unless they hold the 'role.edit' permission.
     */
    protected array $roleHierarchy = [
        'Author'      => 1,
        'Editor'      => 2,
        'Admin'       => 3,
        'Super Admin' => 4,
    ];

    /**
     * Return roles the current actor is allowed to assign.
     */
    protected function assignableRoles(): \Illuminate\Support\Collection
    {
        /** @var User $actor */
        $actor = auth()->user();

        // Super Admin or anyone with role.edit can assign any role
        if ($actor->hasPermissionTo('role.edit')) {
            return Role::orderBy('name')->get();
        }

        // Determine the actor's highest role level
        $actorMaxLevel = 0;
        foreach ($actor->getRoleNames() as $roleName) {
            $level = $this->roleHierarchy[$roleName] ?? 0;
            if ($level > $actorMaxLevel) {
                $actorMaxLevel = $level;
            }
        }

        // May only assign roles strictly below their own level
        $allowed = array_keys(array_filter(
            $this->roleHierarchy,
            fn (int $level) => $level < $actorMaxLevel
        ));

        return Role::whereIn('name', $allowed)->orderBy('name')->get();
    }

    /**
     * Abort 403 if the actor is not allowed to assign the given role name.
     */
    protected function authorizeRoleAssignment(string $roleName): void
    {
        /** @var User $actor */
        $actor = auth()->user();

        if ($actor->hasPermissionTo('role.edit')) {
            return; // full access
        }

        $assignable = $this->assignableRoles()->pluck('name')->toArray();

        if (! in_array($roleName, $assignable, true)) {
            abort(403, 'Anda tidak berhak menetapkan role tersebut.');
        }
    }

    /**
     * Display a listing of users with their roles.
     */
    public function index()
    {
        $users = User::with('roles')->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = $this->assignableRoles();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user with role assignment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'role.exists' => 'Role yang dipilih tidak valid.',
        ]);

        // Security: ensure actor may assign the chosen role
        $this->authorizeRoleAssignment($validated['role']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dibuat.');
    }

    /**
     * Show a user (redirects to edit).
     */
    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = $this->assignableRoles();
        /** @var PermissionRole|null $role */
        $role = $user->roles->first();
        $userRole = $role?->name;

        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified user and role.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'role.exists' => 'Role yang dipilih tidak valid.',
        ]);

        // Security: ensure actor may assign the chosen role
        $this->authorizeRoleAssignment($validated['role']);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Only update password if provided
        if (! empty($validated['password'])) {
            $user->update(['password' => $validated['password']]);
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Prevent deleting the last Super Admin
        if ($user->hasRole('Super Admin')) {
            $superAdminCount = User::role('Super Admin')->count();
            if ($superAdminCount <= 1) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Tidak dapat menghapus Super Admin terakhir.');
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
