<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('updateSettings', $this->route('project')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Wiki
            'wiki_content' => [
                'sometimes',
                'nullable',
                'string',
            ],

            // Document
            'document' => [
                'sometimes',
                'nullable',
                'array',
            ],

            'document.title' => [
                'required_with:document',
                'string',
                'max:255',
            ],

            'document.description' => [
                'nullable',
                'string',
            ]
        ];
    }
}
