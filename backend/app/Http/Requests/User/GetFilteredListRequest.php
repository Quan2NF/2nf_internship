<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Enums\User\UserGender;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GetFilteredListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', User::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'keyword' => ['sometimes', 'nullable', 'string', 'max:255'],

            // active / inactive
            'is_active' => ['sometimes', 'boolean'],

            // enum
            'gender' => [
                'sometimes',
                'nullable',
                Rule::enum(UserGender::class),
            ],

            // join date range
            'join_from' => ['sometimes', 'nullable', 'date', 'before_or_equal:join_to'],
            'join_to'   => ['sometimes', 'nullable', 'date', 'after_or_equal:join_from'],

            // pagination
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
