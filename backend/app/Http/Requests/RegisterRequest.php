<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['email', 'required', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers()
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Digite um nome',
            'email.required' => 'Digite um email',
            'email.email' => 'O email digitado é inválido',
            'email.unique' => 'Um usuário com este email já existe',
            'password.required' => 'Digite uma senha',
            'password.confirmed' => 'A senha de confirmação difere',
        ];
    }
}
