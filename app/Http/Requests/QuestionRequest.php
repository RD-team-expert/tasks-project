<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
        // Assuming a basic structure for a Question model
        return [
            'text' => ['required', 'string', 'max:255', 'unique:questions,text'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'text.required' => 'Question text is required.',
            'text.string' => 'Question text must be a string.',
            'text.max' => 'Question text must not exceed 255 characters.',
            'text.unique' => 'This question already exists.',
        ];
    }
}
