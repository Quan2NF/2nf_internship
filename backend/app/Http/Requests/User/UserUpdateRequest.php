<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_code' => ['sometimes', 'string', 'max:20'],
            'name' => ['sometimes', 'string', 'max:100'],
            'email' => ['sometimes', 'email', 'max:100', 'unique:users,email,' . $this->route('id')],
            'phone_number' => ['nullable', 'string', 'max:15'],

            'birthday' => ['nullable', 'date'],
            'gender' => ['nullable', 'integer', 'in:1,2,3'], // 1 Male, 2 Female, 3 Other

            'join_date' => ['nullable', 'date'],
            'resign_date' => ['nullable', 'date', 'after_or_equal:join_date'],

            'avatar' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'integer', 'in:1,2'], // 1 Active, 2 Inactive
        ];
    }
}
