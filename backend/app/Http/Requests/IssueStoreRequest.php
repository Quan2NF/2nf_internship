<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'type' => ['required', 'in:1,2,3,4'],
            'priority' => ['required', 'in:1,2,3,4'],
            'status' => ['required', 'in:1,2,3,4'],
            'project_id' => ['required', 'exists:projects,id'],
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
            'title.required' => 'Tiêu đề không được để trống',
            'type.required' => 'Loại issue không được để trống',
            'type.in' => 'Loại issue không hợp lệ',
            'priority.required' => 'Độ ưu tiên không được để trống',
            'priority.in' => 'Độ ưu tiên không hợp lệ',
            'status.required' => 'Trạng thái không được để trống',
            'status.in' => 'Trạng thái không hợp lệ',
            'project_id.required' => 'Dự án không được để trống',
            'project_id.exists' => 'Dự án không tồn tại',
            'assigned_to.exists' => 'Người được gán không tồn tại',
            'estimated_hours.numeric' => 'Giờ ước tính phải là số',
            'estimated_hours.min' => 'Giờ ước tính phải >= 0.5',
            'actual_hours.numeric' => 'Giờ thực tế phải là số',
        ];
    }
}
