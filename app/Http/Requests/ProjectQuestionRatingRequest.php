<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectQuestionRatingRequest extends FormRequest
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
        // Assuming typical fields for a ProjectQuestionRating model
        return [
            'project_id' => ['required', 'exists:projects,id'],
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
            'project_id.required' => 'Project ID is required.',
            'project_id.exists' => 'The selected project does not exist.',
            'question_id.required' => 'Question ID is required.',
            'question_id.exists' => 'The selected question does not exist.',
            'rating.required' => 'Rating is required.',
            'rating.integer' => 'Rating must be a number.',
            'rating.between' => 'Rating must be between 1 and 5.',
            'answer.max' => 'Answer must not exceed 1000 characters.',
        ];
    }
}
