<?php

namespace App\Services;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Question;
use App\Models\Task;
use App\Models\TaskQuestionRating;
use App\Models\TaskRating;
use App\Models\User;
use App\Notifications\TaskCompletedNotification;
use Illuminate\Http\Request;

class TaskService
{
    public function getAllTasks()
    {
        $user = auth()->user();
        if ($user->role === 'Admin') {
            return Task::latest()->paginate(10); // Admin sees all tasks
        } elseif ($user->role === 'Manager') {
            return Task::whereIn('project_id', Project::where(function ($query) use ($user) {
                $query->where('created_by', $user->id)
                    ->orWhereHas('employees', function ($subQuery) use ($user) {
                        $subQuery->where('group_id', $user->group_id);
                    });
            })->pluck('id'))->latest()->paginate(10);
        }
        return Task::whereIn('project_id', $user->projects()->pluck('projects.id'))
            ->latest()
            ->paginate(10);
    }

    public function getProjectsForUser(User $user)
    {
        if ($user->role === 'Admin') {
            return Project::all();
        } elseif ($user->role === 'Manager') {
            return Project::where('created_by', $user->id)->get();
        }
        abort(403);
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

    public function createTask(TaskRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        if ($request->has('questions') && count($validated['questions']) > 3) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'questions' => ['You can select a maximum of 3 questions.'],
            ]);
        }

        if ($user->role === 'Manager' && !Project::where('id', $validated['project_id'])->where('created_by', $user->id)->exists()) {
            abort(403, 'You can only create tasks for your own projects.');
        }

        $task = Task::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'project_id' => $validated['project_id'],
            'assigned_to' => $validated['assigned_to'],
            'created_by' => $user->id,
        ]);

        if ($request->has('questions')) {
            foreach ($validated['questions'] as $questionId) {
                $task->questions()->attach($questionId);
            }
        }

        if ($request->filled('new_question')) {
            $newQuestion = Question::create(['text' => $validated['new_question'], 'created_by' => $user->id]);
            $task->questions()->attach($newQuestion->id);
        }

        return $task;
    }

    public function updateTask(Request $request, Task $task)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:Not Started,In Progress,Completed',
        ]);

        $taskRating = TaskRating::create([
            'task_id' => $task->id,
            'review' => null,
        ]);

        foreach ($validated['ratings'] as $questionId => $ratingValue) {
            TaskQuestionRating::create([
                'task_rating_id' => $taskRating->id,
                'question_id' => $questionId,
                'rating' => $ratingValue,
                'answer' => $validated['answers'][$questionId],
            ]);
        }

        return $task->update($validated);
    }

    public function deleteTask(Task $task)
    {
        return $task->delete();
    }

    public function getUserTasks(User $user)
    {
        if ($user->role === 'Admin') {
            return Task::all();
        }
        return Task::where('assigned_to', $user->id)
            ->where('status', '!=', 'Completed')
            ->get();
    }

    public function updateTaskDetails(Request $request, Task $task)
    {
        $user = auth()->user();
        if ($user->role !== 'Admin' && $task->assigned_to !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:Not Started,In Progress,Completed',
            'note' => 'required_if:status,Completed|nullable|string|max:1000',
        ]);
        $task->update(['status' => $validated['status'], 'employee_note' => $validated['note'] ?? null]);
        if ($validated['status'] === 'Completed') {
            $task->creator->notify(new TaskCompletedNotification($task));
        }

        return $task;
    }

    public function submitTaskRating(Request $request, Task $task)
    {
        $user = auth()->user();
        if (!in_array($user->role, [ 'Manager', 'Admin']) || ($user->role === 'Manager' && $task->created_by !== $user->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'ratings.*' => 'required|integer|between:1,5',
            'reasons.*' => 'nullable|required_if:ratings.*,<=,4|string|max:1000',
        ]);

        $taskRating = TaskRating::create([
            'task_id' => $task->id,
            'rating' => collect($validated['ratings'])->avg(),
            'rated_by' => $user->id,
        ]);

        foreach ($validated['ratings'] as $questionId => $ratingValue) {
            TaskQuestionRating::create([
                'task_rating_id' => $taskRating->id,
                'question_id' => $questionId,
                'rating' => $ratingValue,
                'reason' => $ratingValue < 5 ? ($validated['reasons'][$questionId] ?? null) : null,
            ]);
        }

        return $taskRating;
    }
}
