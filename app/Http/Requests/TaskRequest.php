<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Allow all authenticated users; adjust as needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'project_id' => ['required', 'exists:projects,id'],
            'assigned_to' => ['required', 'exists:users,id'],
            'questions' => ['sometimes', 'array'],
            'questions.*' => ['exists:questions,id'],
            'new_question' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', Rule::in(['Not Started', 'In Progress', 'Completed'])],
            'ratings' => ['sometimes', 'array'],
            'ratings.*' => ['required', 'integer', 'between:1,5'],
            'reasons' => ['sometimes', 'array'],
            'reasons.*' => ['nullable', 'required_if:ratings.*,<=,4', 'string', 'max:1000'],
            'answers' => ['sometimes', 'array'],
            'answers.*' => ['sometimes', 'string', 'max:1000'],
            'reason' => ['nullable', 'string', 'max:1000'], // Legacy field, if still used elsewhere
            'note' => ['nullable', 'string', 'max:1000'],
        ];

        $method = $this->method(); // POST, PATCH, PUT, etc.
        $action = $this->route()->getActionMethod(); // e.g., store, update, updateDetails, submitRating

        return match ($action) {
            'store' => collect($rules)->only([
                'name', 'description', 'start_date', 'end_date',
                'project_id', 'assigned_to', 'questions', 'questions.*', 'new_question'
            ])->toArray(),

            'update' => collect($rules)->only([
                'start_date', 'end_date', 'status', 'ratings', 'ratings.*',
                'answers', 'answers.*'
            ])->toArray(),

            'updateDetails' => collect($rules)->only([
                'status', 'note'
            ])->toArray(),

            'submitRating' => collect($rules)->only([
                'ratings', 'ratings.*', 'reasons', 'reasons.*'
            ])->toArray(),

            default => $rules,
        };
        }


    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Task name is required.',
            'description.required' => 'Task description is required.',
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date must be today or later.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'project_id.required' => 'Please select a project.',
            'project_id.exists' => 'Selected project is invalid.',
            'assigned_to.required' => 'Please assign the task to an employee.',
            'assigned_to.exists' => 'Selected employee is invalid.',
            'questions.*.exists' => 'Selected question is invalid.',
            'status.in' => 'Invalid status selected.',
            'ratings.required' => 'Ratings are required.',
            'ratings.*.required' => 'A rating is required for each question.',
            'ratings.*.integer' => 'Ratings must be whole numbers.',
            'ratings.*.between' => 'Ratings must be between 1 and 5.',
            'reasons.*.required_if' => 'A reason is required when the rating is 4 or below.',
            'reasons.*.string' => 'Reason must be text.',
            'reasons.*.max' => 'Reason must not exceed 1000 characters.',
            'answers.*.string' => 'Answers must be text.',
            'answers.*.max' => 'Answers must not exceed 1000 characters.',
            'new_question.max' => 'New question must not exceed 255 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
            'note.max' => 'Note must not exceed 1000 characters.',
            'reason.max' => 'Reason must not exceed 1000 characters.',
            'questions.max' => 'You can select a maximum of 3 questions.',
        ];
    }
}
