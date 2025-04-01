<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRatingRequest;
use App\Models\TaskRating;
use App\Services\TaskRatingService;

class TaskRatingController extends Controller
{
    protected $ratingService;

    public function __construct(TaskRatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $task_ratings = $this->ratingService->getAllRatings();
        return view('task_ratings.index', compact('task_ratings'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('task_ratings.create');
    }

    public function store(TaskRatingRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->createRating($request);
        return redirect()->route('task_ratings.index')->with('success', 'Created successfully');
    }

    public function show(TaskRating $taskRating): \Illuminate\Contracts\View\View
    {
        return view('task_ratings.show', compact('taskRating'));
    }

    public function edit(TaskRating $taskRating): \Illuminate\Contracts\View\View
    {
        return view('task_ratings.edit', compact('taskRating'));
    }

    public function update(TaskRatingRequest $request, TaskRating $taskRating): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->updateRating($request, $taskRating);
        return redirect()->route('task_ratings.index')->with('success', 'Updated successfully');
    }

    public function destroy(TaskRating $taskRating): \Illuminate\Http\RedirectResponse
    {
        $this->ratingService->deleteRating($taskRating);
        return redirect()->route('task_ratings.index')->with('success', 'Deleted successfully');
    }
}
