<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by middleware (auth:sanctum + can:manage users)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'employee_code'),
            ],
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => 'required|string|min:8|max:255',
            'phone_number' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|integer|in:' . User::GENDER_MALE . ',' . User::GENDER_FEMALE . ',' . User::GENDER_OTHER,
            'join_date' => 'nullable|date',
            'role' => 'nullable|string|in:' . User::ROLE_ADMIN . ',' . User::ROLE_MANAGER . ',' . User::ROLE_PM . ',' . User::ROLE_USER,
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'employee_code.required' => 'Mã nhân viên là bắt buộc',
            'employee_code.unique' => 'Mã nhân viên đã tồn tại',
            'name.required' => 'Tên là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'gender.in' => 'Giới tính không hợp lệ',
            'role.in' => 'Vai trò không hợp lệ',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'employee_code' => 'mã nhân viên',
            'name' => 'tên',
            'email' => 'email',
            'password' => 'mật khẩu',
            'phone_number' => 'số điện thoại',
            'birthday' => 'ngày sinh',
            'gender' => 'giới tính',
            'join_date' => 'ngày vào làm',
            'role' => 'vai trò',
            'is_active' => 'trạng thái hoạt động',
        ];
    }
}

