<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],

            'planned_start_date' => ['nullable', 'date'],
            'planned_end_date'   => ['nullable', 'date', 'after_or_equal:planned_start_date'],
            'start_date'         => ['nullable', 'date'],
            'end_date'           => ['nullable', 'date', 'after_or_equal:start_date'],

            'progress_rate' => ['required', 'integer', 'min:0', 'max:100'],
            'is_public'     => ['required', 'integer', 'in:0,1'],
            'is_active'     => ['required', 'integer', 'in:0,1'],
        ];
    }
}
