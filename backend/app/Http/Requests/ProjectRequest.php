<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
   public function rules(): array
{
    return [
        'code' => 'required|string|max:100|unique:projects,code',
        'name' => 'required|string|max:100',
        'description' => 'nullable|string',
        'status' => 'required|in:planning,active,completed,archived',
        'planned_start_date' => 'nullable|date',
        'planned_end_date' => 'nullable|date',
        'progress_rate' => 'required|integer|min:0|max:100',
        'is_public' => 'required|boolean',
    ];
}
}
