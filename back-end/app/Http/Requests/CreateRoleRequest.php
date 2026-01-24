<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:roles,code'],
            'name' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'ERR_INVALID_DATA',
            'code.unique' => 'ERR_ROLE_CODE_EXISTS',
            'name.required' => 'ERR_INVALID_DATA',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first() ?: 'ERR_INVALID_DATA';

        throw new HttpResponseException(response()->json([
            'statusCode' => 422,
            'message' => $message,
        ], 422));
    }
}
