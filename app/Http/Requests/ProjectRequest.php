<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        // Restrict to Admins and Managers, matching createProject logic
        return $user && in_array($user->role, ['Admin', 'Manager']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'employees' => ['sometimes', 'array'], // Changed from assigned_to to match createProject
            'employees.*' => ['exists:users,id', 'distinct'], // Validate each employee ID
            'manager_id' => ['nullable', 'exists:users,id'], // Remains optional for Admins
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'name.max' => 'Project name must not exceed 255 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date must be today or later.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'employees.array' => 'Assigned employees must be provided as an array.',
            'employees.*.exists' => 'Selected employee is invalid.',
            'employees.*.distinct' => 'Duplicate employees selected.',
            'manager_id.exists' => 'Selected manager is invalid.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // If assigned_to is sent instead of employees, convert it
        if ($this->has('assigned_to') && !$this->has('employees')) {
            $this->merge([
                'employees' => $this->assigned_to,
            ]);
        }
    }
}