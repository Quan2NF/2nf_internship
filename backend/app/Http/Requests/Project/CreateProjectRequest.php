<?php

namespace App\Http\Requests\Project;

use App\Models\Project;
use Illuminate\Validation\Rule;
use App\Enums\Project\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Project::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('projects', 'code'),
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'integer',
                Rule::enum(ProjectStatus::class),
            ],

            'planned_start_date' => [
                'nullable',
                'date_format:Y-m-d',
            ],

            'planned_end_date' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:planned_start_date',
            ],

            'start_date' => [
                'nullable',
                'date_format:Y-m-d',
            ],

            'end_date' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],

            'progress_rate' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'is_public' => [
                'sometimes',
                'boolean',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
