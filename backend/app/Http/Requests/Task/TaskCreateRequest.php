<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'parent_id' => ['nullable', 'integer', 'exists:tasks,id'],

            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'status_id' => ['required', 'integer', 'exists:task_statuses,id'],
            'type_id' => ['required', 'integer', 'exists:task_types,id'],
            'priority_id' => ['required', 'integer', 'exists:task_priorities,id'],

            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],

            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],

            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'actual_hours' => ['nullable', 'numeric', 'min:0'],

            'progress_rate' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_private' => ['nullable', 'integer', 'in:0,1'],
            'closed_at' => ['nullable', 'date'],
        ];
    }
}
