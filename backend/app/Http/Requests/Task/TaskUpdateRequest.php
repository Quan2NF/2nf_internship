<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'integer', 'exists:tasks,id'],
            'subject' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'status_id' => ['sometimes', 'integer', 'exists:task_statuses,id'],
            'type_id' => ['sometimes', 'integer', 'exists:task_types,id'],
            'priority_id' => ['sometimes', 'integer', 'exists:task_priorities,id'],

            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],

            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],

            'estimated_hours' => ['nullable', 'numeric'],
            'actual_hours' => ['nullable', 'numeric'],

            'progress_rate' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'is_private' => ['sometimes', 'integer', 'in:0,1'],
            'closed_at' => ['nullable', 'date'],
        ];
    }
}
