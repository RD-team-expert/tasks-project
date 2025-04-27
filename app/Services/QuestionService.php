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
//         dd($request->validated()['text']);

        return Question::create([
            'text' => $request->validated()['text'],
            'created_by' => auth()->user()->id,
        ]);
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
