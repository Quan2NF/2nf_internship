<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['sometimes', 'string', 'max:100'],
            'name' => ['sometimes', 'string', 'max:100'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string', 'max:50'],

            'planned_start_date' => ['sometimes', 'nullable', 'date'],
            'planned_end_date'   => ['sometimes', 'nullable', 'date', 'after_or_equal:planned_start_date'],
            'start_date'         => ['sometimes', 'nullable', 'date'],
            'end_date'           => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],

            'progress_rate' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'is_public'     => ['sometimes', 'integer', 'in:0,1'],
            'is_active'     => ['sometimes', 'integer', 'in:0,1'],
        ];
    }
}
