<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskQuestionRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Allow authenticated users; adjust as needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Assuming typical fields for a TaskQuestionRating model
        return [
            'task_rating_id' => ['required', 'exists:task_ratings,id'],
            'question_id' => ['required', 'exists:questions,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'answer' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'task_rating_id.required' => 'Task rating ID is required.',
            'task_rating_id.exists' => 'The selected task rating does not exist.',
            'question_id.required' => 'Question ID is required.',
            'question_id.exists' => 'The selected question does not exist.',
            'rating.required' => 'Rating is required.',
            'rating.integer' => 'Rating must be an integer.',
            'rating.between' => 'Rating must be between 1 and 5.',
            'answer.max' => 'Answer must not exceed 1000 characters.',
        ];
    }
}
