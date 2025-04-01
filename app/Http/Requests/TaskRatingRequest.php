<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRatingRequest extends FormRequest
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
        // Assuming typical fields for a TaskRating model
        return [
            'task_id' => ['required', 'exists:tasks,id'],
            'rating' => ['required', 'numeric', 'between:1,5'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'review' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'task_id.required' => 'Task ID is required.',
            'task_id.exists' => 'The selected task does not exist.',
            'rating.required' => 'Rating is required.',
            'rating.numeric' => 'Rating must be a number.',
            'rating.between' => 'Rating must be between 1 and 5.',
            'reason.max' => 'Reason must not exceed 1000 characters.',
            'review.max' => 'Review must not exceed 1000 characters.',
        ];
    }
}
