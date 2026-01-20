<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignPositionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'positions' => ['required', 'array', 'min:1'],
            'positions.*.position_id' => ['nullable', 'integer', 'exists:positions,id', 'required_without:positions.*.position_code'],
            'positions.*.position_code' => ['nullable', 'string', 'exists:positions,code', 'required_without:positions.*.position_id'],
            'positions.*.start_date' => ['nullable', 'date'],
            'positions.*.end_date' => ['nullable', 'date'],
        ];
    }
}
