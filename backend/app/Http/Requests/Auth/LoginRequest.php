<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Data\Auth\LoginData;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    //public function toData(): LoginData
    //{
    //    return LoginData::from($this->validated());
    //}
    public function authorize(): bool
    {
        return true;
    }
}
