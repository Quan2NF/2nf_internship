<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AssignPmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pm_id' => ['required', 'integer', 'exists:users,id'],
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
