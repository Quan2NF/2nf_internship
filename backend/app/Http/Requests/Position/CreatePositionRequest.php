<?php

namespace App\Http\Requests\Position;

use App\Models\Position;
use Illuminate\Validation\Rule;
use App\Enums\Position\PositionScope;
use Illuminate\Foundation\Http\FormRequest;

class CreatePositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Position::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('positions', 'code')
                    ->whereNull('deleted_at'),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'scope' => [
                'sometimes',
                Rule::enum(PositionScope::class),
            ],
        ];
    }
}
