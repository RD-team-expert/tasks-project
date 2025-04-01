<?php

namespace App\Services;

use App\Http\Requests\TaskRatingRequest;
use App\Models\TaskRating;

class TaskRatingService
{
    public function getAllRatings()
    {
        return TaskRating::latest()->paginate(10);
    }

    public function createRating(TaskRatingRequest $request)
    {
        return TaskRating::create($request->validated());
    }

    public function updateRating(TaskRatingRequest $request, TaskRating $taskRating)
    {
        return $taskRating->update($request->validated());
    }

    public function deleteRating(TaskRating $taskRating)
    {
        return $taskRating->delete();
    }
}
