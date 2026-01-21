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
        $id = (int) $this->route('id');

        return [
            'employee_code' => ['sometimes', 'string', 'max:20', 'unique:users,employee_code,' . $id],
            'name' => ['sometimes', 'string', 'max:100'],
            'email' => ['sometimes', 'email', 'max:100', 'unique:users,email,' . $id],
            'password' => ['nullable', 'string', 'min:6', 'max:255'],

            'phone_number' => ['nullable', 'string', 'max:15'],
            'birthday' => ['nullable', 'date'],
            'gender' => ['nullable', 'integer', 'in:1,2,3'],
            'join_date' => ['nullable', 'date'],
            'resign_date' => ['nullable', 'date', 'after_or_equal:join_date'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'integer', 'in:1,2'],
        ];
    }
}
