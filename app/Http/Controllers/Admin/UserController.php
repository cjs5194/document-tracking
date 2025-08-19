<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'divisions')->paginate(10); // eager load divisions
        $roles = Role::all();
        $divisions = Division::all(); // fetch all divisions

        return view('admin.users.index', compact('users', 'roles', 'divisions'));
    }

    public function show(User $user)
    {
        $roles = $user->roles;
        $divisions = $user->divisions; // if you want to show divisions too
        return view('admin.users.show', compact('user', 'roles', 'divisions'));
    }

    public function create()
    {
        $roles = Role::all();
        $divisions = Division::all();

        return view('admin.users.create', compact('roles', 'divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'divisions' => 'nullable|array',
            'divisions.*' => 'exists:divisions,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assign roles
        if ($request->has('roles')) {
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->assignRole($roleNames);
        }

        // Assign divisions
        if ($request->has('divisions')) {
            $user->divisions()->sync($request->divisions);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $assignedRoles = $user->roles->pluck('id')->toArray();
        $divisions = Division::all();

        return view('admin.users.edit', compact('user', 'roles', 'assignedRoles', 'divisions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'divisions' => 'nullable|array',
            'divisions.*' => 'exists:divisions,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        // Sync roles
        $roleNames = $request->roles ? Role::whereIn('id', $request->roles)->pluck('name')->toArray() : [];
        $user->syncRoles($roleNames);

        // Sync divisions
        $user->divisions()->sync($request->divisions ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
