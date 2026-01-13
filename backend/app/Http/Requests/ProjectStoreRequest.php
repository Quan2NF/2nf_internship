<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'unique:projects,code', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:1,2,3,4,5'],
            'planned_start_date' => ['nullable', 'date_format:Y-m-d'],
            'planned_end_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:planned_start_date'],
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'progress_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'is_public' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Mã dự án không được để trống',
            'code.unique' => 'Mã dự án đã tồn tại',
            'name.required' => 'Tên dự án không được để trống',
            'status.required' => 'Trạng thái không được để trống',
            'status.in' => 'Trạng thái không hợp lệ',
            'progress_rate.numeric' => 'Tỷ lệ tiến độ phải là số',
            'progress_rate.min' => 'Tỷ lệ tiến độ phải >= 0',
            'progress_rate.max' => 'Tỷ lệ tiến độ phải <= 100',
        ];
    }
}
