<?php

namespace App\Services;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectQuestion;
use App\Models\ProjectRating;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectService
{
    public function getAllProjects()
    {
        $user = auth()->user();
        if ($user->role === 'Admin') {
            return Project::latest()->paginate(10); // Admin sees all projects
        } elseif ($user->role === 'Manager') {
            return Project::where(function ($query) use ($user) {
                // Projects created by the Manager
                $query->where('created_by', $user->id)
                    // OR projects with employees from the Manager's group
                    ->orWhereHas('employees', function ($subQuery) use ($user) {
                        $subQuery->where('group_id', $user->group_id);
                    });
            })->latest()->paginate(10);
        }
        return $user->projects()->latest()->paginate(10);
    }

    public function getManagerProjects()
    {
        $user = auth()->user();
        if ($user->role !== 'Manager') {
            abort(403, 'Only Managers can view assigned projects.');
        }
        return Project::where('manager_id', $user->id)
            ->where('status', '!=', 'Completed')
            ->latest()->paginate(10);
    }
    public function getProjectsForUser(User $user)
    {
        if ($user->role === 'Admin') {
            return Project::all();
        } elseif ($user->role === 'Manager') {
            return Project::whereHas('employees', function ($query) use ($user) {
                $query->where('group_id', $user->group_id);
            })->get();
        }
        return $user->projects()->get();
    }

    public function getEmployeeProjects()
    {
        $currentUser = auth()->user();
        if ($currentUser->role !== 'Employee') {
            abort(403, 'Only Employees can view their assigned projects.');
        }
        return Project::whereHas('employees', function ($query) use ($currentUser) {
            $query->where('user_id', $currentUser->id);
        })->orWhere('assigned_to', $currentUser->id)
          ->latest()
          ->paginate(10);

    }
    public function getProjectEditData(Project $project)
    {
        $user = auth()->user();

        // Authorization check
        if ($user->role !== 'Admin' && $project->created_by !== $user->id) {
            abort(403, 'You can only edit your own projects unless you are Admin.');
        }

        // Get available employees to assign
        $employees = $user->role === 'Admin'
            ? User::where('role', 'Employee')->get()
            : User::where('group_id', $user->group_id)->where('role', 'Employee')->get();

        // Get available managers (only for Admin role)
        $managers = $user->role === 'Admin'
            ? User::where('role', 'Manager')->get()
            : collect();

        // Return all data needed by the edit view
        return [
            'project' => $project->load('employees', 'manager'),
            'employees' => $employees,
            'managers' => $managers,
            'selectedEmployees' => $project->employees()->pluck('users.id')->toArray(),
        ];
    }

    public function getManagerGroupEmployees()
    {
        $manager = auth()->user();
        if ($manager->role === 'Admin') {
            return User::where('role', 'Employee')->get();
        }
        if (!$manager->group_id) {
            return collect();
        }
        return User::where('group_id', $manager->group_id)
            ->where('role', 'Employee')
            ->get();
    }

    public function createProject(ProjectRequest $request)
{
    $user = auth()->user();
    $validated = $request->validated();

    if (!in_array($user->role, ['Admin', 'Manager'])) {
        abort(403, 'Only Admins and Managers can create projects.');
    }

    // Determine manager_id based on role
    $managerId = $user->role === 'Admin'
        ? ($validated['manager_id'] ?? null)
        : $user->id; // Managers should assign themselves by default

    $project = Project::create([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
        'manager_id' => $managerId,
        'created_by' => $user->id,
        'status' => 'Pending',
    ]);

    // Handle employee assignments
    if ($request->has('employees') ) {
        // Ensure employees is an array and attach
        $employees = is_array($validated['employees'])
            ? $validated['employees']
            : [$validated['employees']];
        $project->employees()->attach($employees);
    }

    // Return the project with relationships loaded for verification
    return $project->load('employees', 'creator');
}

    public function submitProjectRating(Request $request, Project $project)
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can rate projects.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:1000',
        ]);

        $rating = ProjectRating::create([
            'project_id' => $project->id,
            'rating' => $validated['rating'],
            'review' => $validated['review'],
            'rated_by' => $user->id,
        ]);

        return $rating;
    }

    public function getProjectRatings(Project $project)
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can view project ratings.');
        }
        return $project->ratings()->with('rater')->get();
    }

    public function deleteProject(Project $project)
    {
        $user = auth()->user();
        if ($user->role !== 'Admin' && $project->created_by !== $user->id) {
            abort(403, 'You can only delete your own projects unless you are Admin.');
        }
        $project->delete();
        return true;
    }

    public function getCompletedProjects()
    {
        $user = auth()->user();
        if ($user->role === 'Admin') {
            return Project::where('status', 'Completed')->latest()->paginate(10);
        } elseif ($user->role === 'Manager') {
            return Project::where('manager_id', $user->id)
                ->where('status', 'Completed')
                ->latest()->paginate(10);
        }
        return collect(); // Employees don’t see completed projects
    }



    public function updateStatus(Request $request, Project $project)
    {
        $user = auth()->user();
        if ($user->role !== 'Manager' || $project->manager_id !== $user->id) {
            abort(403, 'Only the assigned Manager can update project status.');
        }

        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            'notes' => 'nullable|string|max:1000', // Optional notes when completed
        ]);

        $project->update([
            'status' => $validated['status'],
            'notes' => $validated['status'] === 'Completed' ? $validated['notes'] : $project->notes,
        ]);

        return $project;
    }

    public function addQuestion(Request $request, Project $project)
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can add project questions.');
        }

        $validated = $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        return ProjectQuestion::create([
            'project_id' => $project->id,
            'question' => $validated['question'],
        ]);
    }

    public function rateQuestion(Request $request, ProjectQuestion $question)
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can rate project questions.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        $question->update([
            'rating' => $validated['rating'],
            'rated_by' => $user->id,
        ]);

        return $question;
    }

    public function getProjectQuestions(Project $project)
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can view project questions.');
        }
        return $project->questions()->with('rater')->get();
    }


}
