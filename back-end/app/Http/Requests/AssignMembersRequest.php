<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AssignMembersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'members' => ['required', 'array'],
            'members.*' => ['integer', 'exists:users,id'],
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
