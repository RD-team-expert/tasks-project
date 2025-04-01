<?php

namespace App\Services;

use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class GroupService
{
    public function getPaginatedGroups(int $perPage = 10): LengthAwarePaginator
    {
        $user = auth()->user();
        if ($user->role === 'Admin') {
            return Group::latest()->paginate($perPage); // Admin sees all groups
        } elseif ($user->role === 'Manager') {
            return Group::where('id', $user->group_id)->latest()->paginate($perPage); // Manager sees their group
        }
        abort(403, 'Unauthorized action.');
    }

    public function createGroup(GroupRequest $request)
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can create groups.');
        }

        $validated = $request->validated();
        $group = Group::create([
            'name' => $validated['name'],
            'manager_id' => $validated['manager_id'],
        ]);

        // Assign the Manager to the group
        User::where('id', $validated['manager_id'])->update(['group_id' => $group->id]);

        if (!empty($validated['employees'])) {
            User::whereIn('id', $validated['employees'])->update(['group_id' => $group->id]);
        }

        return $group;
    }

    public function findGroup(Group $group): Group
    {
        $user = auth()->user();
        if ($user->role !== 'Admin' && $group->id !== $user->group_id) {
            abort(403, 'Unauthorized action.');
        }
        return $group;
    }

    public function updateGroup(Group $group, GroupRequest $request): bool
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can update groups.');
        }

        $validated = $request->validated();
        $data = [
            'name' => $validated['name'],
            'manager_id' => $validated['manager_id'],
        ];

        if (isset($validated['employees'])) {
            User::where('group_id', $group->id)
                ->whereNotIn('id', $validated['employees'])
                ->update(['group_id' => null]);
            User::whereIn('id', $validated['employees'])
                ->update(['group_id' => $group->id]);
        }

        return $group->update($data);
    }

    public function deleteGroup(Group $group): bool
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can delete groups.');
        }

        User::where('group_id', $group->id)->update(['group_id' => null]);
        return $group->delete();
    }
}
