<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class GetFilteredTaskListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', [Task::class, $this->route('project')]) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status_id'    => ['nullable', 'integer', 'exists:task_statuses,id'],
            'type_id'      => ['nullable', 'integer', 'exists:task_types,id'],
            'priority_id'  => ['nullable', 'integer', 'exists:task_priorities,id'],
            'assigned_to'  => ['nullable', 'integer', 'exists:users,id'],
            'created_by'   => ['nullable', 'integer', 'exists:users,id'],
            'is_private'   => ['nullable', 'boolean'],

            'keyword'      => ['nullable', 'string', 'max:255'],

            'start_date_from' => ['nullable', 'date_format:Y-m-d'],
            'start_date_to'   => ['nullable', 'date_format:Y-m-d'],
            'due_date_from'   => ['nullable', 'date_format:Y-m-d'],
            'due_date_to'     => ['nullable', 'date_format:Y-m-d'],

            'page'         => ['nullable', 'integer', 'min:1'],
            'per_page'     => ['nullable', 'integer', 'min:1', 'max:100'],

            'sort_by'      => [
                'nullable',
                'string',
                'in:id,start_date,due_date,priority_id,status_id,created_at',
            ],
            'sort_dir'     => ['nullable', 'string', 'in:asc,desc'],
        ];
    }
}
