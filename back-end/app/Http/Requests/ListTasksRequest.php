<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListTasksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'status_id' => ['nullable', 'integer'],
            'project_id' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
