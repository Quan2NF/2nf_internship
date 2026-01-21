<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // quyền admin sẽ check ở policy
    }

    public function rules(): array
    {
        return [
            'employee_code' => ['required', 'string', 'max:20', 'unique:users,employee_code'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:255'],

            'phone_number' => ['nullable', 'string', 'max:15'],
            'birthday' => ['nullable', 'date'],
            'gender' => ['nullable', 'integer', 'in:1,2,3'], // 1 male 2 female 3 other
            'join_date' => ['nullable', 'date'],
            'resign_date' => ['nullable', 'date', 'after_or_equal:join_date'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'integer', 'in:1,2'], // 1 Active, 2 Inactive
        ];
    }
}
