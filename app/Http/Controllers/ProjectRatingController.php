<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRatingRequest;
use App\Models\ProjectRating;
use App\Services\ProjectRatingService;

class ProjectRatingController extends Controller
{
    protected $ratingService;

    public function __construct(ProjectRatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $project_ratings = $this->ratingService->getAllRatings();
        return view('project_ratings.index', compact('project_ratings'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('project_ratings.create');
    }

    public function store(ProjectRatingRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->createRating($request);
        return redirect()->route('project_ratings.index')->with('success', 'Created successfully');
    }

    public function show(ProjectRating $projectRating): \Illuminate\Contracts\View\View
    {
        return view('project_ratings.show', compact('projectRating'));
    }

    public function edit(ProjectRating $projectRating): \Illuminate\Contracts\View\View
    {
        return view('project_ratings.edit', compact('projectRating'));
    }

    public function update(ProjectRatingRequest $request, ProjectRating $projectRating): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->updateRating($request, $projectRating);
        return redirect()->route('project_ratings.index')->with('success', 'Updated successfully');
    }

    public function destroy(ProjectRating $projectRating): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->deleteRating($projectRating);
        return redirect()->route('project_ratings.index')->with('success', 'Deleted successfully');
    }
}
