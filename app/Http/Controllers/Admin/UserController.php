<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::staff()->withCount(['assignedEnquiries as open_enquiries_count' => fn ($q) => $q->open()])
                ->orderByDesc('is_active')->orderBy('name')->get(),
            'roles' => config('roles.roles'),
            'permissions' => config('roles.permissions'),
        ]);
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User(['role' => 'sales', 'is_active' => true]), 'roles' => config('roles.roles')]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $generated = blank($data['password'] ?? null);
        $data['password'] = $generated ? Str::password(12) : $data['password'];

        $user = User::create($data);
        $user->forceFill(['email_verified_at' => now()])->saveQuietly();

        return redirect()->route('admin.users.index')->with('status', $generated
            ? "Staff account created for {$user->email}. Temporary password: {$data['password']} — share it securely; it won't be shown again."
            : "Staff account created for {$user->email}.");
    }

    public function edit(User $user)
    {
        $this->ensureStaffAccount($user);

        return view('admin.users.form', ['user' => $user, 'roles' => config('roles.roles')]);
    }

    public function update(Request $request, User $user)
    {
        $this->ensureStaffAccount($user);
        $data = $this->validated($request, $user);

        if ($user->is($request->user()) && ($data['role'] !== $user->role || ! $data['is_active'])) {
            throw ValidationException::withMessages(['role' => 'You cannot change your own role or deactivate your own account.']);
        }
        $this->ensureAnAdminRemains($user, $data['role'], $data['is_active']);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', "Updated {$user->name}.");
    }

    public function destroy(Request $request, User $user)
    {
        $this->ensureStaffAccount($user);
        if ($user->is($request->user())) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }
        $this->ensureAnAdminRemains($user, null, false);

        $user->delete();

        return back()->with('status', "Deleted {$user->name}. Their assigned enquiries are now unassigned.");
    }

    protected function validated(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')->ignore($user)],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', Rule::in(array_keys(config('roles.roles')))],
            'password' => ['nullable', 'string', Password::min(8)],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    /** This screen only manages staff accounts (active or deactivated), never other user types. */
    protected function ensureStaffAccount(User $user): void
    {
        abort_unless(array_key_exists((string) $user->role, config('roles.roles')), 404);
    }

    /** Never allow the last active administrator to be demoted, deactivated or deleted. */
    protected function ensureAnAdminRemains(User $user, ?string $newRole, bool $active): void
    {
        $losesAdmin = $user->isAdmin() && $user->is_active && ($newRole !== 'admin' || ! $active);

        if ($losesAdmin && User::where('role', 'admin')->where('is_active', true)->whereKeyNot($user->id)->doesntExist()) {
            throw ValidationException::withMessages(['role' => 'At least one active Administrator is required.']);
        }
    }
}
