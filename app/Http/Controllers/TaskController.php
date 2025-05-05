<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Question;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $tasks = $this->taskService->getAllTasks();
        return view('tasks.index', compact('tasks'));
    }

    public function myTasks()
    {
        $user = auth()->user();
        $tasks = $this->taskService->getUserTasks($user);
        return view('tasks.my-tasks', compact('tasks'));
    }

    public function create()
    {
        $user = auth()->user();
        $projects = $this->taskService->getProjectsForUser($user);
        $employees = $this->taskService->getManagerGroupEmployees();
        $questions = Question::all();

        return view('tasks.create', compact('projects', 'employees', 'questions'));
    }

    public function store(TaskRequest $request): RedirectResponse
    {
        $this->taskService->createTask($request);
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->taskService->updateTask($request, $task);

        if (auth()->user()->role === 'Employee') {
            return redirect()->route('my.tasks')->with('success', 'Task details updated.');
        }

        return redirect()->route('tasks.index')->with('success', 'Task details updated.');
    }

    public function updateDetails(TaskRequest $request, Task $task)
    {
        $this->taskService->updateTaskDetails($request, $task);
        return redirect()->route('tasks.myTasks')->with('success', 'Task updated.');
    }

    public function submitRating(TaskRequest $request, Task $task)
    {
        $this->taskService->submitTaskRating($request, $task);
        return redirect()->route('tasks.index')->with('success', 'Task rated.');
    }

    public function editDetails(Task $task)
    {
        if ($task->assigned_to !== auth()->id()) {
            abort(403);
        }
        $questions = Question::all();
        return view('tasks.edit-details', compact('task', 'questions'));
    }

    public function show(Task $task): \Illuminate\Contracts\View\View
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $data = $this->taskService->edit($task);
        return view('tasks.edit', $data);
    }
}
