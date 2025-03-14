<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTodoRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'unique:todos'],
            'description' => ['required'],
            'user_id' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Digite um título',
            'title.unique' => 'Já existe uma tarefa com este nome',
            'description.required' => 'Digite uma descrição',
            'user_id.required' => 'Digite seu ID de usuário'
        ];
    }
}
