<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'integer', 'in:1,2'],
            'position_code' => ['nullable', 'string', 'max:100'],
            'position_id' => ['nullable', 'integer', 'min:1'],
            'join_date_from' => ['nullable', 'date'],
            'join_date_to' => ['nullable', 'date'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:200'],
            // sort examples: name, -join_date
            'sort' => ['nullable', 'string', 'max:50'],
        ];
    }
}
