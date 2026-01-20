<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $routeUser = $this->route('user');
        $userId = $routeUser instanceof User ? $routeUser->id : $routeUser;

        return [
            'employee_code' => ['sometimes', 'string', 'max:20', 'unique:users,employee_code,'.$userId],
            'name' => ['sometimes', 'string', 'max:100'],
            'email' => ['sometimes', 'email', 'max:100', 'unique:users,email,'.$userId],
            'password' => ['sometimes', 'nullable', 'string', 'min:6', 'max:255'],

            'phone_number' => ['sometimes', 'nullable', 'string', 'max:15'],
            'birthday' => ['sometimes', 'nullable', 'date'],
            'gender' => ['sometimes', 'nullable', 'integer', 'in:1,2,3'],
            'join_date' => ['sometimes', 'nullable', 'date'],
            'resign_date' => ['sometimes', 'nullable', 'date'],
            'avatar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'nullable', 'integer', 'in:1,2'],

            'positions' => ['sometimes', 'array'],
            'positions.*.position_id' => ['nullable', 'integer', 'exists:positions,id', 'required_without:positions.*.position_code'],
            'positions.*.position_code' => ['nullable', 'string', 'exists:positions,code', 'required_without:positions.*.position_id'],
            'positions.*.start_date' => ['nullable', 'date'],
            'positions.*.end_date' => ['nullable', 'date'],
        ];
    }
}
