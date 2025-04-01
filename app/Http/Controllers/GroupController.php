<?php

namespace App\Http\Controllers;

use App\Services\GroupService;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index()
    {
        $groups = $this->groupService->getPaginatedGroups();
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $managers = \App\Models\User::where('role', 'Manager')->get();
        $employees = \App\Models\User::where('role', 'Employee')->get();
        return view('groups.create', compact('managers', 'employees'));
    }

    public function store(\App\Http\Requests\GroupRequest $request)
    {
        $this->groupService->createGroup($request);
        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
    }

    public function edit(Group $group)
    {
        $group = $this->groupService->findGroup($group);
        $managers = \App\Models\User::where('role', 'Manager')->get();
        $employees = \App\Models\User::where('role', 'Employee')->get();
        return view('groups.edit', compact('group', 'managers', 'employees'));
    }

    public function update(Group $group, \App\Http\Requests\GroupRequest $request)
    {
        $this->groupService->updateGroup($group, $request);
        return redirect()->route('groups.index')->with('success', 'Group updated successfully.');
    }

    public function destroy(Group $group)
    {
        $this->groupService->deleteGroup($group);
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }
}
