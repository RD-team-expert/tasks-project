<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskQuestionRatingRequest;
use App\Models\TaskQuestionRating;
use App\Services\TaskQuestionRatingService;

class TaskQuestionRatingController extends Controller
{
    protected $ratingService;

    public function __construct(TaskQuestionRatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $task_question_ratings = $this->ratingService->getAllRatings();
        return view('task_question_ratings.index', compact('task_question_ratings'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('task_question_ratings.create');
    }

    public function store(TaskQuestionRatingRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->createRating($request);
        return redirect()->route('task_question_ratings.index')->with('success', 'Created successfully');
    }

    public function show(TaskQuestionRating $taskQuestionRating): \Illuminate\Contracts\View\View
    {
        return view('task_question_ratings.show', compact('taskQuestionRating'));
    }

    public function edit(TaskQuestionRating $taskQuestionRating): \Illuminate\Contracts\View\View
    {
        return view('task_question_ratings.edit', compact('taskQuestionRating'));
    }

    public function update(TaskQuestionRatingRequest $request, TaskQuestionRating $taskQuestionRating): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->updateRating($request, $taskQuestionRating);
        return redirect()->route('task_question_ratings.index')->with('success', 'Updated successfully');
    }

    public function destroy(TaskQuestionRating $taskQuestionRating): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->deleteRating($taskQuestionRating);
        return redirect()->route('task_question_ratings.index')->with('success ranges', 'Deleted successfully');
    }
}
