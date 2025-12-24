<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6', // password_confirmation phải gửi kèm
            'employee_code' => 'required|string|unique:users,employee_code',
            'phone_number' => 'nullable|string|max:15',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:1,2,3',
        ];
    }
}
