<?php

namespace App\Services;

use App\Http\Requests\PositionRequest;
use App\Models\Position;
use Illuminate\Pagination\LengthAwarePaginator;

class PositionService
{
    public function getPaginatedPositions(int $perPage = 10): LengthAwarePaginator
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can view positions.');
        }
        return Position::latest()->paginate($perPage);
    }

    public function createPosition(PositionRequest $request): Position
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can create positions.');
        }

        $validated = $request->validated();
        return Position::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);
    }

    public function updatePosition(Position $position, PositionRequest $request): bool
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can update positions.');
        }

        $validated = $request->validated();
        return $position->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);
    }

    public function deletePosition(Position $position): bool
    {
        $user = auth()->user();
        if ($user->role !== 'Admin') {
            abort(403, 'Only Admin can delete positions.');
        }
        return $position->delete();
    }
}
