<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', [Task::class, $this->route('project')]) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('tasks', 'id')
                    ->where('project_id', $this->route('project')->id)
                    ->whereNot('id', $this->route('task')?->id),
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status_id' => [
                'required',
                'integer',
                Rule::exists('task_statuses', 'id'),
            ],

            'type_id' => [
                'required',
                'integer',
                Rule::exists('task_types', 'id'),
            ],

            'priority_id' => [
                'required',
                'integer',
                Rule::exists('task_priorities', 'id'),
            ],

            'assigned_to' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id'),
            ],

            'start_date' => [
                'nullable',
                'date_format:Y-m-d',
            ],

            'due_date' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],

            'estimated_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'actual_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'progress_rate' => [
                'required',
                'integer',
                'between:0,100',
            ],

            'is_private' => [
                'boolean',
            ],

            'closed_at' => [
                'nullable',
                'date_format:Y-m-d H:i:s',
            ],
        ];
    }
}
