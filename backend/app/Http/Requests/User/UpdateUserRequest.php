<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Enums\User\UserGender;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('user')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100'],

            'phone_number' => ['sometimes', 'nullable', 'string', 'max:15'],

            'birthday' => ['sometimes', 'nullable', 'date'],

            'gender' => [
                'sometimes',
                'nullable',
                Rule::enum(UserGender::class),
            ],

            'avatar' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
