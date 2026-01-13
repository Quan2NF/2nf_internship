<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'type' => ['sometimes', 'in:1,2,3,4'],
            'priority' => ['sometimes', 'in:1,2,3,4'],
            'status' => ['sometimes', 'in:1,2,3,4'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0.5', 'max:999.99'],
            'actual_hours' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'due_date' => ['nullable', 'date_format:Y-m-d'],
            'resolution' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'Loại issue không hợp lệ',
            'priority.in' => 'Độ ưu tiên không hợp lệ',
            'status.in' => 'Trạng thái không hợp lệ',
            'assigned_to.exists' => 'Người được gán không tồn tại',
            'estimated_hours.numeric' => 'Giờ ước tính phải là số',
            'estimated_hours.min' => 'Giờ ước tính phải >= 0.5',
            'actual_hours.numeric' => 'Giờ thực tế phải là số',
        ];
    }
}
