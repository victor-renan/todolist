<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['email', 'required'],
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Digite um email',
            'email.email' => 'O email digitado é inválido',
            'password.required' => 'Digite uma senha',
        ];
    }
}
