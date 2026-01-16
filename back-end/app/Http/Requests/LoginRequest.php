<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required','string','email'],
            'password' => ['required','string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'ERR_INVALID_DATA',
            'email.email' => 'ERR_INVALID_DATA',
            'password.required' => 'ERR_INVALID_DATA',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'statusCode' => 422,
            'message' => 'ERR_INVALID_DATA',
        ], 422));
    }
}
