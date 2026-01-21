<?php

namespace App\Http\Requests\Project;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AssignMembersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('assignMembers', $this->route('project')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'members' => ['required', 'array', 'min:1'],

            'members.*.user_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists(User::class, 'id'),
            ],

            'members.*.roles' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    if (count($value) !== count(array_unique($value))) {
                        $fail('Roles must be unique per member.');
                    }
                },
            ],

            'members.*.roles.*' => [
                'string',
                Rule::exists(Role::class, 'code'),
                Rule::notIn(['PM']),
            ],
        ];
    }
}
