<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = (int) $this->route('id');

        return [
            'code' => ['sometimes', 'string', 'max:20', 'unique:roles,code,' . $id],
            'name' => ['sometimes', 'string', 'max:100'],
        ];
    }
}
