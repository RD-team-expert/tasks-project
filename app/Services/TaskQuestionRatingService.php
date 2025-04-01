<?php

namespace App\Services;

use App\Http\Requests\TaskQuestionRatingRequest;
use App\Models\TaskQuestionRating;

class TaskQuestionRatingService
{
    public function getAllRatings()
    {
        return TaskQuestionRating::latest()->paginate(10);
    }

    public function createRating(TaskQuestionRatingRequest $request)
    {
        return TaskQuestionRating::create($request->validated());
    }

    public function updateRating(TaskQuestionRatingRequest $request, TaskQuestionRating $taskQuestionRating)
    {
        return $taskQuestionRating->update($request->validated());
    }

    public function deleteRating(TaskQuestionRating $taskQuestionRating)
    {
        return $taskQuestionRating->delete();
    }
}
