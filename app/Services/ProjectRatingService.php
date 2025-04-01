<?php

namespace App\Services;

use App\Http\Requests\ProjectRatingRequest;
use App\Models\ProjectRating;

class ProjectRatingService
{
    public function getAllRatings()
    {
        return ProjectRating::latest()->paginate(10);
    }

    public function createRating(ProjectRatingRequest $request)
    {
        return ProjectRating::create($request->validated());
    }

    public function updateRating(ProjectRatingRequest $request, ProjectRating $projectRating)
    {
        return $projectRating->update($request->validated());
    }

    public function deleteRating(ProjectRating $projectRating)
    {
        return $projectRating->delete();
    }
}
