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
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'role_codes' => ['required', 'array', 'min:1'],
            'role_codes.*' => ['string', 'exists:roles,code'],
        ];
    }
}
