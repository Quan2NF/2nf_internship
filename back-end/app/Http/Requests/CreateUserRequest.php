<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_code' => ['nullable', 'string', 'max:20', 'unique:users,employee_code'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:6', 'max:255'],

            'phone_number' => ['required', 'string', 'max:15'],
            'birthday' => ['nullable', 'date'],
            'gender' => ['nullable', 'integer', 'in:1,2,3'],
            'join_date' => ['required', 'date'],
            'resign_date' => ['nullable', 'date'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'integer', 'in:1,2'],

            'positions' => ['required', 'array', 'min:1'],
            'positions.*.position_id' => ['nullable', 'integer', 'exists:positions,id', 'required_without:positions.*.position_code'],
            'positions.*.position_code' => ['nullable', 'string', 'exists:positions,code', 'required_without:positions.*.position_id'],
            'positions.*.start_date' => ['nullable', 'date'],
            'positions.*.end_date' => ['nullable', 'date'],
        ];
    }
}
