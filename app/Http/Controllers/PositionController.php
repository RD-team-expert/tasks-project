<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PositionRequest;
use App\Models\Position;
use App\Services\PositionService;

class PositionController extends Controller
{
    protected $positionService;

    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $positions = $this->positionService->getAllPositions();
        return view('positions.index', compact('positions'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('positions.create');
    }

    public function store(PositionRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->positionService->createPosition($request);
        return redirect()->route('positions.index')->with('success', 'Created successfully');
    }

    public function show(Position $position): \Illuminate\Contracts\View\View
    {
        return view('positions.show', compact('position'));
    }

    public function edit(Position $position): \Illuminate\Contracts\View\View
    {
        return view('positions.edit', compact('position'));
    }

    public function update(PositionRequest $request, Position $position): \Illuminate\Http\RedirectResponse
    {
        $this->positionService->updatePosition($request, $position);
        return redirect()->route('positions.index')->with('success', 'Updated successfully');
    }

    public function destroy(Position $position): \Illuminate\Http\RedirectResponse
    {
        $this->positionService->deletePosition($position);
        return redirect()->route('positions.index')->with('success', 'Deleted successfully');
    }
}
