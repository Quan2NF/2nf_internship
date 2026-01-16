<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Enums\User\UserGender;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', User::class) ?? false;
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
                'max:20',
                'unique:users,employee_code',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users,email',
            ],

            'phone_number' => [
                'nullable',
                'string',
                'max:15',
            ],

            'birthday' => [
                'nullable',
                'date',
            ],

            'gender' => [
                'nullable',
                Rule::enum(UserGender::class),
            ],

            'join_date' => [
                'nullable',
                'date',
            ],

            'resign_date' => [
                'nullable',
                'date',
                'after_or_equal:join_date',
            ],

            'avatar' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
