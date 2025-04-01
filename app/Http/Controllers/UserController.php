<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): View
    {
        $users = $this->userService->getAllUsers();
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $groups = \App\Models\Group::all(); // For Admin to assign groups
        return view('users.create', compact('groups'));
    }

    public function store(UserRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->userService->createUser($request);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $groups = \App\Models\Group::all();
        return view('users.edit', compact('user', 'groups'));
    }

    public function update(UserRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $this->userService->updateUser($request, $user);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        $this->userService->deleteUser($user);
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function createEmployee(): View
    {
        return view('users.create-employee');
    }

    public function storeEmployee(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->userService->createEmployee($request);
        return redirect()->route('users.index')->with('success', 'Employee created successfully.');
    }
}
