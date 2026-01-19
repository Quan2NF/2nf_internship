<?php

namespace App\Http\Requests\Position;

use App\Models\Position;
use Illuminate\Validation\Rule;
use App\Enums\Position\PositionScope;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', Position::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $position = $this->route('position');

        return [
            'code' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('positions', 'code')
                    ->ignore($position->id)
                    ->whereNull('deleted_at'),
            ],
            'name' => [
                'sometimes',
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
