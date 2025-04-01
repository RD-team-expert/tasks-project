<?php

namespace App\Services;

use App\Http\Requests\ProjectQuestionRatingRequest;
use App\Models\ProjectQuestionRating;

class ProjectQuestionRatingService
{
    public function getAllRatings()
    {
        return ProjectQuestionRating::latest()->paginate(10);
    }

    public function createRating(ProjectQuestionRatingRequest $request)
    {
        return ProjectQuestionRating::create($request->validated());
    }

    public function updateRating(ProjectQuestionRatingRequest $request, ProjectQuestionRating $projectQuestionRating)
    {
        return $projectQuestionRating->update($request->validated());
    }

    public function deleteRating(ProjectQuestionRating $projectQuestionRating)
    {
        return $projectQuestionRating->delete();
    }
}
