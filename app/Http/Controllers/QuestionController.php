<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use App\Services\QuestionService;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $questions = $this->questionService->getAllQuestions();
        return view('questions.index', compact('questions'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('questions.create');
    }

    public function store(QuestionRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->questionService->createQuestion($request);
        return redirect()->route('questions.index')->with('success', 'Created successfully');
    }

    public function show(Question $question): \Illuminate\Contracts\View\View
    {
        return view('questions.show', compact('question'));
    }

    public function edit(Question $question): \Illuminate\Contracts\View\View
    {
        return view('questions.edit', compact('question'));
    }

    public function update(QuestionRequest $request, Question $question): \Illuminate\Http\RedirectResponse
    {
        $this->questionService->updateQuestion($request, $question);
        return redirect()->route('questions.index')->with('success', 'Updated successfully');
    }

    public function destroy(Question $question): \Illuminate\Http\RedirectResponse
    {
        $this->questionService->deleteQuestion($question);
        return redirect()->route('questions.index')->with('success', 'Deleted successfully');
    }
}
