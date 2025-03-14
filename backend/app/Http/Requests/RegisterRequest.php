<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['email', 'required'],
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
            'password.required' => 'Digite uma senha',
            'password.confirmed' => 'A senha de confirmação difere',
        ];
    }
}
