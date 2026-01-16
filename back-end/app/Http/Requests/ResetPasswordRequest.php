<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'ERR_INVALID_DATA',
            'email.email' => 'ERR_INVALID_DATA',
            'token.required' => 'ERR_INVALID_DATA',
            'password.required' => 'ERR_INVALID_DATA',
            'password.min' => 'ERR_INVALID_DATA',
            'password.confirmed' => 'ERR_INVALID_DATA',
        ];
    }
}
