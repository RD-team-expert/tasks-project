<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'employees' => 'nullable|array',
            'employees.*' => 'exists:users,id',
        ];
    }
}
