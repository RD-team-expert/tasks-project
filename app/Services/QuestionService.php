<?php

namespace App\Services;

use App\Http\Requests\QuestionRequest;
use App\Models\Question;

class QuestionService
{
    public function getAllQuestions()
    {
        return Question::latest()->paginate(10);
    }

    public function createQuestion(QuestionRequest $request)
    {
        return Question::create($request->validated());
    }

    public function updateQuestion(QuestionRequest $request, Question $question)
    {
        return $question->update($request->validated());
    }

    public function deleteQuestion(Question $question)
    {
        return $question->delete();
    }
}
