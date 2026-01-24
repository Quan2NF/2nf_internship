<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['sometimes', 'required', 'string', 'max:50'],
            'name' => ['sometimes', 'required', 'string', 'max:191'],
            'kickoff_date' => ['sometimes', 'required', 'date'],
            'duration_days' => ['sometimes', 'required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
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
