<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'role_codes' => ['required', 'array', 'min:1'],
            'role_codes.*' => ['required', 'string', 'exists:roles,code'],
            'mode' => ['nullable', 'string', 'in:sync,append'],
        ];
    }
}
