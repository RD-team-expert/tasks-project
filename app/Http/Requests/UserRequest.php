<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $method = $this->method();

        switch ($method) {
            case 'POST':
                return [
                    'username' => 'required|string|max:255',
                    'email' => 'required|string|max:255|email|unique:users,email',
                    'password' => 'required|string|min:8|max:255',
                    'role' => 'required|in:Admin,Manager,Employee',
                    'position_id' => 'nullable|integer|exists:positions,id',
                    'group_id' => 'nullable|integer|exists:groups,id',
                ];
            case 'PUT':
                return [
                    'username' => 'nullable|string|max:255',
                    'email' => 'nullable|string|max:255|email|unique:users,email,' . $this->user?->id,
                    'password' => 'nullable|string|min:8|max:255',
                    'role' => 'nullable|in:Admin,Manager,Employee',
                    'position_id' => 'nullable|integer|exists:positions,id',
                    'group_id' => 'nullable|integer|exists:groups,id',
                ];
            default:
                return [];
        }
    }
}
