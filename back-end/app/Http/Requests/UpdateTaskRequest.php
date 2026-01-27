<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'subject' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status_id' => ['nullable', 'integer', 'exists:task_statuses,id'],
            'type_id' => ['nullable', 'integer', 'exists:task_types,id'],
            'priority_id' => ['nullable', 'integer', 'exists:task_priorities,id'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'numeric'],
            'actual_hours' => ['nullable', 'numeric'],
            'progress_rate' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_private' => ['nullable', 'integer', 'in:0,1'],
            'closed_at' => ['nullable', 'date'],
        ];
    }
}
