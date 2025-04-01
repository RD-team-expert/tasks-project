<?php

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    public function getAllUsers()
    {
        $user = auth()->user();
        if ($user->role === 'Admin') {
            return User::latest()->paginate(10); // Admin sees all users
        } elseif ($user->role === 'Manager') {
            return User::where('group_id', $user->group_id)
                ->where('role', 'Employee')
                ->latest()->paginate(10); // Manager sees group users
        }
        abort(403, 'Unauthorized action.');
    }

    public function createUser(UserRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can create users with any role.');
        }

        return User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'group_id' => $validated['group_id'] ?? null,
            'manager_id' => $validated['manager_id'] ?? null,
            'position_id' => $validated['position_id'] ?? null,
        ]);
    }

    public function createEmployee(Request $request)
    {
        $manager = auth()->user();
        if ($manager->role !== 'Manager') {
            abort(403, 'Only Managers can create employees.');
        }

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        return User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'Employee',
            'group_id' => $manager->group_id, // Automatically set to Manager’s group
        ]);
    }
    public function updateUser(UserRequest $request, User $user)
    {
        $authUser = auth()->user();
        if ($authUser->role !== 'Admin') {
            abort(403, 'Only Admin can update users.');
        }

        $validated = $request->validated();

        $user->update([
            'username' => $validated['username'] ?? $user->username,
            'email' => $validated['email'] ?? $user->email,
            'password' => isset($validated['password']) ? bcrypt($validated['password']) : $user->password,
            'role' => $validated['role'] ?? $user->role,
            'position_id' => $validated['position_id'] ?? $user->position_id,
            'group_id' => $validated['group_id'] ?? $user->group_id,
        ]);

        return $user;
    }

    public function deleteUser(User $user)
    {
        $authUser = auth()->user();
        if ($authUser->role !== 'Admin') {
            abort(403, 'Only Admin can delete users.');
        }

        $user->delete();
        return true;
    }
}
