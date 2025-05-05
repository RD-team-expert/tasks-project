<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\ProjectQuestion;
use App\Services\ProjectService;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $projects = Project::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function myProjects()
    {
        $projects = $this->projectService->getEmployeeProjects();
        return view('projects.my-Projects', compact('projects')); // Reusing the manager view
    }
    public function managerProjects()
    {
        $projects = $this->projectService->getManagerProjects();
        return view('projects.manager', compact('projects'));
    }

    public function completed()
    {
        $completedProjects = $this->projectService->getCompletedProjects();
        return view('projects.completed', compact('completedProjects'));
    }

    public function create()
    {
        $users = $this->projectService->getManagerGroupEmployees();
        return view('projects.create', compact('users'));
    }

    public function store(ProjectRequest $request)
    {
        $this->projectService->createProject($request);
        return redirect()->route('projects.index')->with('success', 'Project created.');
    }

    public function show(Project $project)
    {
        $user = auth()->user();
        if ($user->role === 'Admin') {
            $questions = $this->projectService->getProjectQuestions($project);
            return view('projects.show', compact('project', 'questions'));
        } elseif ($user->role === 'Manager' && $project->manager_id === $user->id) {
            return view('projects.show', compact('project'));
        }
        abort(403, 'Unauthorized.');
    }

    public function updateStatus(Request $request, Project $project)
    {
        $this->projectService->updateStatus($request, $project);
        return redirect()->route('projects.manager')->with('success', 'Status updated.');
    }
    public function update(Request $request, Project $project)
    {
        $this->projectService->update($request, $project);
        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully.');    }

    public function addQuestion(Request $request, Project $project)
    {
        $this->projectService->addQuestion($request, $project);
        return redirect()->back()->with('success', 'Question added.');
    }

    public function rateQuestion(Request $request, ProjectQuestion $question)
    {
        $this->projectService->rateQuestion($request, $question);
        return redirect()->back()->with('success', 'Question rated.');
    }

    public function edit(Project $project)
    {
      $data=  $this->projectService->getProjectEditData($project);
        return view('projects.edit', $data);
    }
}
