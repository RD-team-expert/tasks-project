<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectQuestionRatingRequest;
use App\Models\ProjectQuestionRating;
use App\Services\ProjectQuestionRatingService;

class ProjectQuestionRatingController extends Controller
{
    protected $ratingService;

    public function __construct(ProjectQuestionRatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $project_question_ratings = $this->ratingService->getAllRatings();
        return view('project_question_ratings.index', compact('project_question_ratings'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('project_question_ratings.create');
    }

    public function store(ProjectQuestionRatingRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->createRating($request);
        return redirect()->route('project_question_ratings.index')->with('success', 'Created successfully');
    }

    public function show(ProjectQuestionRating $projectQuestionRating): \Illuminate\Contracts\View\View
    {
        return view('project_question_ratings.show', compact('projectQuestionRating'));
    }

    public function edit(ProjectQuestionRating $projectQuestionRating): \Illuminate\Contracts\View\View
    {
        return view('project_question_ratings.edit', compact('projectQuestionRating'));
    }

    public function update(ProjectQuestionRatingRequest $request, ProjectQuestionRating $projectQuestionRating): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->updateRating($request, $projectQuestionRating);
        return redirect()->route('project_question_ratings.index')->with('success', 'Updated successfully');
    }

    public function destroy(ProjectQuestionRating $projectQuestionRating): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->deleteRating($projectQuestionRating);
        return redirect()->route('project_question_ratings.index')->with('success', 'Deleted successfully');
    }
}
